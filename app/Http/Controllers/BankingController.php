<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{Account, Beneficiary, Transaction};
use Illuminate\Support\Str;

class BankingController extends Controller
{
    // --- 1.1 MFA / OTP ---
    public function showOtpForm() {
        if (Auth::user()->is_mfa_verified) return redirect()->route('dashboard');
        
        // Generate and log OTP
        $otp = rand(100000, 999999);
        $user = Auth::user();
        $user->update(['otp_code' => Hash::make($otp), 'otp_expires_at' => now()->addMinutes(10)]);
        
        // In a real app, email this. Here, we log it so you can see it in storage/logs/laravel.log
        \Log::info("OTP for {$user->email} is: {$otp}");
        
        return view('banking.otp');
    }

    public function verifyOtp(Request $request) {
        $request->validate(['otp' => 'required|numeric']);
        $user = Auth::user();
        
        if (now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired.']);
        }
        
        if (Hash::check($request->otp, $user->otp_code)) {
            $user->update(['is_mfa_verified' => true, 'otp_code' => null]);
            
            // Auto-create account on first login
            if (!$user->account) {
                Account::create([
                    'user_id' => $user->id,
                    'account_number' => 'DB' . date('Y') . rand(10000, 99999),
                    'balance' => 10000.00 // Giving initial dummy balance
                ]);
            }
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['otp' => 'Invalid OTP Code.']);
    }

    // --- 1.2 Dashboard & Balance ---
    public function dashboard() {
        $account = Auth::user()->account;
        $transactions = $account ? $account->transactions()->latest()->take(5)->get() : [];
        return view('banking.dashboard', compact('account', 'transactions'));
    }

    // --- 1.3 Transfer & Beneficiaries ---
    public function transferForm() {
        $beneficiaries = Auth::user()->beneficiaries;
        return view('banking.transfer', compact('beneficiaries'));
    }

    public function processTransfer(Request $request) {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'account_number' => 'required|string',
            'description' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $account = $user->account;

        if ($account->balance < $request->amount) {
            return back()->withErrors(['amount' => 'Insufficient Balance']);
        }
        if ($request->amount > $user->daily_transaction_limit) {
            return back()->withErrors(['amount' => 'Amount exceeds your daily limit of $' . number_format($user->daily_transaction_limit, 2)]);
        }

        // Deduct balance
        $account->decrement('balance', $request->amount);
        
        // Record Transaction
        Transaction::create([
            'account_id' => $account->id,
            'type' => 'debit',
            'amount' => $request->amount,
            'description' => 'Transfer to ' . $request->account_number . ' - ' . $request->description,
            'reference_no' => 'TRX' . strtoupper(Str::random(10))
        ]);

        // If internal transfer, add to recipient (Simulated)
        $recipientAccount = Account::where('account_number', $request->account_number)->first();
        if ($recipientAccount) {
            $recipientAccount->increment('balance', $request->amount);
            Transaction::create([
                'account_id' => $recipientAccount->id,
                'type' => 'credit',
                'amount' => $request->amount,
                'description' => 'Transfer from ' . $account->account_number,
                'reference_no' => 'TRX' . strtoupper(Str::random(10))
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Transfer Successful');
    }

    public function addBeneficiary(Request $request) {
        $request->validate(['name' => 'required', 'account_number' => 'required', 'bank_name' => 'required']);
        Auth::user()->beneficiaries()->create($request->all());
        return back()->with('success', 'Beneficiary Added');
    }

    // --- 1.4 Profile & Export History ---
    public function exportHistory() {
        $transactions = Auth::user()->account->transactions()->latest()->get();
        $csvHeader = "Date,Reference,Type,Description,Amount\n";
        $csvData = "";
        foreach ($transactions as $t) {
            $csvData .= "{$t->created_at},{$t->reference_no},{$t->type},{$t->description},{$t->amount}\n";
        }
        
        return response($csvHeader . $csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="transaction_history.csv"');
    }
}