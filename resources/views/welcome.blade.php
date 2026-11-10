<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DIU BANK - Secure Digital Banking</title>
    
    <!-- Failsafe Tailwind CDN to guarantee the design loads perfectly immediately -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#0B1120] font-sans selection:bg-blue-500 selection:text-white">
    
    <!-- Professional Navbar -->
    <nav class="absolute top-0 w-full z-50 border-b border-white/10 bg-[#0B1120]/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-20 items-center">
            
            <!-- Modern DIU BANK Logo -->
            <div class="text-2xl font-black tracking-tighter text-white flex items-center gap-2">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-1.5 rounded-lg shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                DIU <span class="text-blue-400">BANK</span>
            </div>

            <!-- Navigation Links -->
            <div class="flex gap-4 items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="font-semibold text-white hover:text-blue-400 transition">Go to Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="font-medium text-slate-300 hover:text-white transition px-4 py-2">Log in</a>
                    <a href="{{ route('register') }}" class="font-semibold bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-500 shadow-lg shadow-blue-900/20 border border-blue-500 transition">Open Account</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex flex-col justify-center pt-24 overflow-hidden bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-[#0B1120] to-[#0B1120]">
        
        <!-- Background Ambient Glows -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full filter blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-600/10 rounded-full filter blur-[100px] pointer-events-none"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-10">
            <!-- Project Badge -->
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white/5 border border-white/10 text-blue-300 text-sm font-medium mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Fall 2026 Project • Group 01
            </span>
            
            <!-- Main Headline -->
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 text-white leading-tight">
                Secure Digital Banking <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">For The Modern Era</span>
            </h1>
            
            <!-- Subtitle -->
            <p class="mt-6 text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-12 leading-relaxed">
                Experience ultra-secure financial management with Multi-Factor Authentication, real-time transfers, and intelligent fraud detection.
            </p>
            
            <!-- High-Contrast Buttons -->
            <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-500 shadow-xl shadow-blue-900/50 border border-blue-500 transition-all flex items-center justify-center gap-2">
                    Get Started Now
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold rounded-xl text-white bg-white/5 hover:bg-white/10 border border-white/10 backdrop-blur-sm transition-all">
                    Client Login
                </a>
            </div>
        </div>

        <!-- Professional Banking Trust Indicators -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 mb-12 w-full">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-white/10 pt-10">
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-900/30 flex items-center justify-center mb-5 text-blue-400 border border-blue-500/20 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h4 class="text-white font-bold mb-2 text-lg">MFA Security</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Bank with absolute confidence using our advanced Multi-Factor OTP Authentication protocol.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-900/30 flex items-center justify-center mb-5 text-blue-400 border border-blue-500/20 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-white font-bold mb-2 text-lg">Real-Time Transfers</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Send, receive, and manage your funds instantly with zero processing delays between accounts.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-blue-900/30 flex items-center justify-center mb-5 text-blue-400 border border-blue-500/20 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h4 class="text-white font-bold mb-2 text-lg">Fraud Detection</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Intelligent, real-time monitoring keeps your digital assets and identity completely protected.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>