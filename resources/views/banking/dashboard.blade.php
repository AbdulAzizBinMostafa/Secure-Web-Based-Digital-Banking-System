<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- SUCCESS MESSAGE ALERT -->
            @if(session('success'))
                <div class="p-4 border-l-4 border-green-500 text-green-800 rounded-r-lg bg-green-50 shadow-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- BALANCE CREDIT CARD -->
            <div class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 rounded-3xl p-8 shadow-2xl text-white overflow-hidden max-w-md">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10 blur-xl"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-white opacity-10 blur-lg"></div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-medium tracking-widest uppercase flex items-center gap-2">
                            @if(session('duress_mode'))
                                <span class="text-red-400 font-bold flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                    SAFE MODE ACTIVE
                                </span>
                            @else
                                <span class="text-blue-200">Available Balance</span>
                            @endif
                        </h3>
                        <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <p class="text-5xl font-black tracking-tight mb-8">${{ number_format($account->balance ?? 0, 2) }}</p>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-xs text-blue-300 uppercase tracking-widest mb-1">Account Number</p>
                            <p class="font-mono text-lg tracking-widest">{{ $account->account_number ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-blue-300 uppercase tracking-widest">Status</span>
                            <p class="font-semibold text-green-400 capitalize">{{ $account->status ?? 'Active' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODULE 3: ADMIN & FRAUD MANAGEMENT TOOLS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Naimul's Payment Link -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -z-10"></div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">TTL Payment Link</h3>
                    </div>
                    <p class="text-sm text-slate-500 mb-5">Create a secure payment URL and QR Code that self-destructs in exactly 15 minutes.</p>
                    
                    <form method="POST" action="{{ route('payment.create') }}" class="flex gap-3">
                        @csrf
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold">$</div>
                            <input type="number" step="0.01" name="amount" placeholder="0.00" required class="block w-full pl-7 border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition px-3 py-2.5 bg-slate-50">
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">Generate</button>
                    </form>

                    @if(session('pay_link'))
                        <div class="mt-5 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl shadow-sm">
                            <p class="font-bold text-indigo-800 text-sm mb-2 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                Link Active (Valid for 15m):
                            </p>
                            <a href="{{ session('pay_link') }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 transition underline text-sm break-all font-medium">{{ session('pay_link') }}</a>
                            <div class="mt-3 bg-white p-2 rounded-xl inline-block shadow-sm border border-indigo-100">
                                <img src="{{ session('qr_code') }}" alt="QR Code" class="w-24 h-24">
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Mahim's Virtual Account -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden flex flex-col">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-bl-full -z-10"></div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-slate-800 text-white rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Disposable Virtual Card</h3>
                    </div>
                    <p class="text-sm text-slate-500 mb-6 flex-grow">Generate a secure one-time account number for unsafe online purchases to completely protect your main balance from theft.</p>
                    
                    <form method="POST" action="{{ route('virtual.create') }}">
                        @csrf
                        <button type="submit" class="bg-slate-800 text-white px-4 py-3.5 rounded-xl text-sm font-bold hover:bg-slate-900 w-full shadow-lg shadow-slate-200 transition transform hover:-translate-y-0.5">
                            Generate Virtual Account Number
                        </button>
                    </form>
                </div>
            </div>

            <!-- RECENT TRANSACTIONS TABLE -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Recent Transactions</h3>
                    <a href="{{ route('history.export') }}" class="text-sm font-semibold bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-full shadow-sm hover:bg-slate-50 hover:text-blue-600 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export CSV
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $trx->created_at->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-mono">
                                    {{ $trx->reference_no }}
                                    @if($trx->is_flagged)
                                        <span class="ml-3 px-2.5 py-1 bg-red-100 text-red-700 border border-red-200 text-[10px] uppercase rounded-full font-bold tracking-widest shadow-sm" title="{{ $trx->fraud_reason }}">
                                            Flagged
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-medium">{{ $trx->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $trx->type == 'credit' ? 'text-green-600' : 'text-slate-900' }}">
                                    {{ $trx->type == 'credit' ? '+' : '-' }}${{ number_format($trx->amount, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">No transactions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>