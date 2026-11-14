<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{Account, Beneficiary, Transaction, AdminAlert, PaymentLink, VirtualAccount};

class BankingController extends Controller
{
    // --- 1.1 & 3.3 MFA / OTP & Panic PIN ---
    public function showOtpForm() {
        if (Auth::user()->is_mfa_verified) return redirect()->route('dashboard');
        
        $otp = rand(100000, 999999);
        $user = Auth::user();
        $user->update(['otp_code' => Hash::make($otp), 'otp_expires_at' => now()->addMinutes(10)]);
        
        \Log::info("OTP for {$user->email} is: {$otp}");
        
        return view('banking.otp', ['testOtp' => $otp]);
    }

    public function verifyOtp(Request $request) {
        $request->validate(['otp' => 'required|numeric']);
        $user = Auth::user();
        
        // MD NAIMUL HAQUE: Panic PIN Check
        if ($request->otp == '999999' || Hash::check($request->otp, $user->panic_pin)) {
            AdminAlert::create([
                'user_id' => $user->id,
                'type' => 'duress_login',
                'message' => 'CRITICAL: User logged in using Panic PIN. They may be under physical threat.'
            ]);
            
            session(['duress_mode' => true]);
            $user->update(['is_mfa_verified' => true]);
            
            return redirect()->route('dashboard');
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired.']);
        }
        
        if (Hash::check($request->otp, $user->otp_code)) {
            $user->update(['is_mfa_verified' => true, 'otp_code' => null]);
            
            if (!$user->account) {
                Account::create([
                    'user_id' => $user->id,
                    'account_number' => 'DB' . date('Y') . rand(10000, 99999),
                    'balance' => 10000.00
                ]);
            }
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['otp' => 'Invalid OTP Code.']);
    }

    // --- 1.2 & 3.3 Dashboard & Duress Balance ---
    public function dashboard() {
        $account = Auth::user()->account;
        $transactions = $account ? $account->transactions()->latest()->take(5)->get() : [];
        
        // MD NAIMUL HAQUE: Fake Balance injection if in Duress Mode
        if (session('duress_mode') && $account) {
            $account->balance = rand(15, 60) + (rand(0, 99) / 100); 
        }

        return view('banking.dashboard', compact('account', 'transactions'));
    }

    // --- 1.3 Transfer & Beneficiaries ---
    public function transferForm() {
        $beneficiaries = Auth::user()->beneficiaries;
        return view('banking.transfer', compact('beneficiaries'));
    }

    public function addBeneficiary(Request $request) {
        $request->validate(['name' => 'required', 'account_number' => 'required', 'bank_name' => 'required']);
        Auth::user()->beneficiaries()->create($request->all());
        return back()->with('success', 'Beneficiary Added');
    }

    // --- 1.3 & 3.1 Core Transfer & Fraud Engine ---
    public function processTransfer(Request $request) {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'account_number' => 'required|string',
            'description' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $account = $user->account;

        if ($account->balance < $request->amount) return back()->withErrors(['amount' => 'Insufficient Balance']);
        
        if ($request->amount > $user->daily_transaction_limit) {
            return back()->withErrors(['amount' => 'Amount exceeds your daily limit of $' . number_format($user->daily_transaction_limit, 2)]);
        }

        // --- ABDUL AZIZ: Behavioral Fraud Detection Engine ---
        $is_flagged = false;
        $fraud_reason = null;
        
        $currentHour = now()->hour;
        $isLateNight = ($currentHour >= 1 && $currentHour <= 5);
        
        $averageTransfer = Transaction::where('account_id', $account->id)
                            ->where('type', 'debit')->avg('amount') ?? 500; 

        if ($isLateNight && $request->amount >= 5000) {
            $is_flagged = true;
            $fraud_reason = 'Suspiciously high transfer amount ('.$request->amount.') initiated at late night hour ('.$currentHour.':00).';
        } elseif ($request->amount > ($averageTransfer * 5)) {
            $is_flagged = true;
            $fraud_reason = 'Transaction amount ('.$request->amount.') is significantly higher than normal user habit (Avg: '.$averageTransfer.').';
        }

        if ($is_flagged) {
            AdminAlert::create([
                'user_id' => $user->id,
                'type' => 'fraud_flag',
                'message' => $fraud_reason
            ]);
        }
        // --- End of Fraud Logic ---

        $account->decrement('balance', $request->amount);
        
        Transaction::create([
            'account_id' => $account->id,
            'type' => 'debit',
            'amount' => $request->amount,
            'description' => 'Transfer to ' . $request->account_number . ' - ' . $request->description,
            'reference_no' => 'TRX' . strtoupper(Str::random(10)),
            'is_flagged' => $is_flagged,
            'fraud_reason' => $fraud_reason
        ]);

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

    // --- 1.4 History ---
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

    // --- 3.2 MD NAIMUL HAQUE: Generate TTL Link ---
    public function createPaymentLink(Request $request) {
        $request->validate(['amount' => 'required|numeric|min:1']);
        
        $token = Str::uuid();
        
        PaymentLink::create([
            'user_id' => Auth::id(),
            'token' => $token,
            'amount' => $request->amount,
            'expires_at' => now()->addMinutes(15)
        ]);

        $url = route('payment.pay', ['token' => $token]);
        $qrCode = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($url);

        return back()->with('success', 'Secure 15-Minute Payment Link Generated!')->with('qr_code', $qrCode)->with('pay_link', $url);
    }

    public function processPaymentLink($token) {
        $link = PaymentLink::where('token', $token)->firstOrFail();
        
        if ($link->is_used) {
            return "This payment link has already been used and is destroyed.";
        }
        if (now()->greaterThan($link->expires_at)) {
            return "SECURITY ALERT: This payment link expired at " . $link->expires_at . " and has been automatically destroyed.";
        }

        $link->update(['is_used' => true]);
        return "Payment of $" . number_format($link->amount, 2) . " processed successfully! The link is now destroyed.";
    }

    // --- 3.4 MAHIM TALUKDER APURBO: Generate Disposable Account ---
    public function createVirtualAccount() {
        $virtualNumber = 'VIRT-' . rand(10000000, 99999999);
        
        VirtualAccount::create([
            'user_id' => Auth::id(),
            'virtual_number' => $virtualNumber
        ]);

        return back()->with('success', 'Disposable Virtual Account created: ' . $virtualNumber . '. This number will self-destruct after one transaction.');
    }
}