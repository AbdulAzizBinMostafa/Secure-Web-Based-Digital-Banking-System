<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">Fund Transfer</h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Send Money Form -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -z-10"></div>
            
            <div class="flex items-center gap-3 mb-6">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Send Money</h3>
            </div>

            <form method="POST" action="{{ route('transfer') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Recipient Account Number</label>
                    <input id="account_number" type="text" name="account_number" required class="block w-full border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition px-4 py-3 bg-slate-50" placeholder="e.g. DB202612345">
                    
                    <select onchange="document.getElementById('account_number').value = this.value" class="mt-3 block w-full border-slate-200 text-slate-600 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-3 bg-white text-sm">
                        <option value="">⚡ Quick Select Saved Beneficiary</option>
                        @foreach($beneficiaries as $ben)
                            <option value="{{ $ben->account_number }}">{{ $ben->name }} - {{ $ben->bank_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Amount (Limit: ${{ number_format(Auth::user()->daily_transaction_limit, 0) }})</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold">$</div>
                        <input type="number" step="0.01" name="amount" required class="block w-full pl-8 border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-3 bg-slate-50" placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Remarks / Description</label>
                    <input type="text" name="description" required class="block w-full border-slate-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition px-4 py-3 bg-slate-50" placeholder="What is this for?">
                </div>
                <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-200 transition transform hover:-translate-y-0.5">
                    Confirm Transfer
                </button>
            </form>
        </div>

        <!-- Add Beneficiary Form -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Add Beneficiary</h3>
            </div>

            <form method="POST" action="{{ route('beneficiary.add') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Account Name</label>
                    <input type="text" name="name" required class="block w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Account Number</label>
                    <input type="text" name="account_number" required class="block w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Bank Name</label>
                    <input type="text" name="bank_name" required class="block w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition px-4 py-3 bg-slate-50" placeholder="e.g. DIU BANK">
                </div>
                <button type="submit" class="w-full mt-4 bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5">
                    Save Beneficiary
                </button>
            </form>
        </div>
    </div>
</x-app-layout>