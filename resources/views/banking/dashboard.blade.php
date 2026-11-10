<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 border-l-4 border-green-500 text-green-800 rounded-r-lg bg-green-50 shadow-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Balance Credit Card Style -->
            <div class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 rounded-3xl p-8 shadow-2xl text-white overflow-hidden max-w-md">
                <!-- Decorative circles inside card -->
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10 blur-xl"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-white opacity-10 blur-lg"></div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-medium tracking-widest text-blue-200 uppercase">Available Balance</h3>
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

            <!-- Recent Transactions Table -->
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-mono">{{ $trx->reference_no }}</td>
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