<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Maintenance — WebsolAI</title>

    <link rel="icon" type="image/png" sizes="32x32" href="/images/logo.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-16px); }
        }
        @keyframes pulse-ring {
            0%   { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.2); opacity: 0; }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to   { transform: rotate(0deg); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        @keyframes bounce-dot {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40%           { transform: scale(1);   opacity: 1;   }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0);    }
        }

        .float       { animation: float 4s ease-in-out infinite; }
        .spin-slow   { animation: spin-slow 12s linear infinite; }
        .spin-reverse { animation: spin-reverse 18s linear infinite; }

        .shimmer-text {
            background: linear-gradient(
                90deg,
                #818cf8 0%,
                #a78bfa 25%,
                #c4b5fd 50%,
                #a78bfa 75%,
                #818cf8 100%
            );
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }

        .dot-1 { animation: bounce-dot 1.4s ease-in-out infinite; }
        .dot-2 { animation: bounce-dot 1.4s ease-in-out 0.2s infinite; }
        .dot-3 { animation: bounce-dot 1.4s ease-in-out 0.4s infinite; }

        .fade-up { animation: fade-up 0.7s ease both; }
        .fade-up-1 { animation: fade-up 0.7s 0.1s ease both; }
        .fade-up-2 { animation: fade-up 0.7s 0.25s ease both; }
        .fade-up-3 { animation: fade-up 0.7s 0.4s ease both; }
        .fade-up-4 { animation: fade-up 0.7s 0.55s ease both; }

        .pulse-ring {
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            border: 2px solid rgba(99, 102, 241, 0.5);
            animation: pulse-ring 2.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        .pulse-ring-2 {
            animation-delay: 0.8s;
        }
        .pulse-ring-3 {
            animation-delay: 1.6s;
        }

        .progress-bar {
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #6366f1);
            background-size: 200% 100%;
            animation: shimmer 2s linear infinite;
        }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center overflow-hidden">

    {{-- Background blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-64 -right-64 w-[600px] h-[600px] bg-indigo-600/15 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-64 -left-64 w-[600px] h-[600px] bg-violet-600/15 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[900px] h-[900px] bg-indigo-900/10 rounded-full blur-3xl"></div>
        {{-- Dot grid --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(99,102,241,0.07) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative z-10 max-w-2xl mx-auto px-6 py-16 text-center">

        {{-- Logo --}}
        <div class="fade-up flex justify-center mb-10">
            <a href="/" class="inline-flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-xl overflow-hidden shadow-lg shadow-indigo-500/20 ring-1 ring-indigo-500/30 flex-shrink-0">
                    <img src="/images/logo.png" alt="WebsolAI" class="w-full h-full object-cover" style="object-position: 50% 15%;">
                </div>
                <span class="text-xl font-bold tracking-tight text-white">WebsolAI</span>
            </a>
        </div>

        {{-- Animated gear icon --}}
        <div class="fade-up-1 flex justify-center mb-10">
            <div class="relative w-32 h-32 float">
                {{-- Pulse rings --}}
                <span class="pulse-ring"></span>
                <span class="pulse-ring pulse-ring-2"></span>
                <span class="pulse-ring pulse-ring-3"></span>

                {{-- Outer spinning ring --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-28 h-28 rounded-full border-2 border-dashed border-indigo-500/30 spin-slow"></div>
                </div>
                {{-- Inner spinning ring --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-20 h-20 rounded-full border-2 border-dashed border-violet-500/40 spin-reverse"></div>
                </div>

                {{-- Center icon --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 shadow-xl shadow-indigo-500/30 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Badge --}}
        <div class="fade-up-2 flex justify-center mb-6">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-sm font-medium">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Scheduled Maintenance
            </span>
        </div>

        {{-- Headline --}}
        <h1 class="fade-up-2 text-4xl sm:text-5xl font-bold tracking-tight mb-4 leading-tight">
            We'll be back<br>
            <span class="shimmer-text">very soon</span>
        </h1>

        {{-- Sub-text --}}
        <p class="fade-up-3 text-slate-400 text-lg leading-relaxed mb-10 max-w-lg mx-auto">
            Our team is performing scheduled maintenance to improve your experience.
            We apologize for the inconvenience and appreciate your patience.
        </p>

        {{-- Progress bar --}}
        <div class="fade-up-3 mb-10">
            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                <span>Maintenance in progress</span>
                <span class="flex items-center gap-1.5">
                    Working
                    <span class="flex gap-1">
                        <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full dot-1"></span>
                        <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full dot-2"></span>
                        <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full dot-3"></span>
                    </span>
                </span>
            </div>
            <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                <div class="progress-bar h-full w-3/5 rounded-full"></div>
            </div>
        </div>

        {{-- Info cards --}}
        <div class="fade-up-3 grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 text-center hover:border-indigo-500/40 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/15 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-white mb-1">Security Update</p>
                <p class="text-xs text-slate-500">Applying latest patches</p>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 text-center hover:border-violet-500/40 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-violet-600/15 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-white mb-1">Performance</p>
                <p class="text-xs text-slate-500">Optimizing infrastructure</p>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 text-center hover:border-indigo-500/40 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-indigo-600/15 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-white mb-1">New Features</p>
                <p class="text-xs text-slate-500">Rolling out improvements</p>
            </div>
        </div>

        {{-- CTA --}}
        <div class="fade-up-4 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="mailto:hello@websolai.com"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-600/30 hover:shadow-indigo-500/40 hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact Us
            </a>
            <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-800 text-slate-300 text-sm font-semibold hover:bg-slate-700 hover:text-white transition-all border border-slate-700 hover:border-slate-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Try Again
            </button>
        </div>

        {{-- Footer --}}
        <p class="fade-up-4 mt-14 text-xs text-slate-600">
            &copy; {{ date('Y') }} WebsolAI. All rights reserved.
            &nbsp;&middot;&nbsp;
            <a href="mailto:hello@websolai.com" class="hover:text-slate-400 transition-colors">hello@websolai.com</a>
        </p>
    </div>

</body>
</html>
