<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ config('app.name', 'Real Estate Pro') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-building text-white"></i>
                    </div>
                    <span class="text-2xl font-bold text-slate-900">Real Estate Pro</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                {{ $slot }}
            </div>

            {{-- Back to Home --}}
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

</body>
</html>
