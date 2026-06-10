@extends('layouts.app')

@section('title', 'About Us — WebsolAI')
@section('meta_description', 'Learn about WebsolAI — our story, mission, values, and the expert team behind the digital products we build.')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-slate-950 to-indigo-950 text-white py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-indigo-400 font-semibold text-sm uppercase tracking-widest">About WebsolAI</span>
            <h1 class="text-5xl sm:text-6xl font-bold mt-4 mb-6 leading-tight">
                We Are a Team of <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Digital Builders</span>
            </h1>
            <p class="text-xl text-slate-300 leading-relaxed">
                Founded in 2019, WebsolAI is a full-service digital agency specialising in web and mobile application development. We help businesses of all sizes build and scale their digital presence with precision, creativity, and purpose.
            </p>
        </div>
    </div>
</section>

{{-- Story --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">Our Story</span>
                <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-6">From a Small Studio to a Trusted Agency</h2>
                <div class="space-y-5 text-slate-500 leading-relaxed">
                    <p>
                        WebsolAI was born out of a simple frustration: too many businesses were being let down by agencies that promised the world and delivered mediocrity. Our founders — experienced engineers and designers with roots in enterprise software — decided to build something different.
                    </p>
                    <p>
                        Starting with just three people in 2019, we focused exclusively on quality. We took fewer projects, spent more time on each one, and built genuine relationships with our clients. Word spread quickly. Within two years, we had grown to a team of twenty, serving clients across four continents.
                    </p>
                    <p>
                        Today, WebsolAI is a team of 30+ specialists — engineers, designers, project managers, and strategists — united by a shared passion for building digital products that truly work. We remain proudly independent, client-first, and obsessed with craft.
                    </p>
                </div>
            </div>

            <div class="relative">
                <div class="bg-gradient-to-br from-indigo-50 to-violet-50 rounded-3xl p-10 border border-indigo-100">
                    <div class="grid grid-cols-2 gap-6">
                        @php
                        $facts = [
                            ['value' => '2019', 'label' => 'Year Founded'],
                            ['value' => '30+', 'label' => 'Team Members'],
                            ['value' => '150+', 'label' => 'Projects Delivered'],
                            ['value' => '4', 'label' => 'Continents Served'],
                            ['value' => '20+', 'label' => 'Industries Covered'],
                            ['value' => '98%', 'label' => 'Client Satisfaction'],
                        ];
                        @endphp
                        @foreach ($facts as $fact)
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-indigo-50">
                            <div class="text-3xl font-bold text-indigo-600 mb-1">{{ $fact['value'] }}</div>
                            <div class="text-sm text-slate-500 font-medium">{{ $fact['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">What Drives Us</span>
            <h2 class="text-4xl font-bold text-slate-900 mt-3">Mission & Vision</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-10 border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Our Mission</h3>
                <p class="text-slate-500 leading-relaxed">
                    To empower businesses with technology by delivering thoughtfully crafted digital solutions that create real, measurable impact. We exist to make exceptional software accessible to every business — from ambitious startups to established enterprises.
                </p>
            </div>

            <div class="bg-slate-900 rounded-2xl p-10 text-white">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4">Our Vision</h3>
                <p class="text-slate-300 leading-relaxed">
                    To be the most trusted digital development partner for growing businesses worldwide — known not just for the quality of our work, but for the integrity of our relationships and the long-term success of our clients.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Values --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">Our Core Values</span>
            <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-4">The Principles We Build On</h2>
            <p class="text-slate-500 text-lg">These values guide every decision we make, every line of code we write, and every relationship we build.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $values = [
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Quality First', 'desc' => 'We never cut corners. Every deliverable goes through rigorous review before it reaches you. Good enough is never good enough for us.'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Client Partnership', 'desc' => 'We treat every client as a long-term partner, not a transaction. Your success is our success — we invest in your goals as if they were our own.'],
                ['icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Transparency', 'desc' => 'No surprises, no hidden costs. We communicate openly at every step — about progress, challenges, and timelines.'],
                ['icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'title' => 'Innovation', 'desc' => 'We stay at the forefront of technology so you don\'t have to. We continuously learn and apply new tools and approaches to solve problems better.'],
                ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Reliability', 'desc' => 'We deliver what we promise, when we promise it. Our track record of on-time delivery is something we protect fiercely.'],
                ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => 'Passion', 'desc' => 'We genuinely love what we do. That passion shows in the care, creativity, and attention to detail we bring to every single project.'],
            ];
            @endphp
            @foreach ($values as $value)
            <div class="p-8 rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-50 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition-colors">
                    <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $value['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-3">{{ $value['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $value['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Team --}}
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">The People Behind the Work</span>
            <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-4">Meet Our Leadership Team</h2>
            <p class="text-slate-500 text-lg">A diverse group of experts passionate about technology and dedicated to your success.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
            $team = [
                ['name' => 'Prosenjit Pramanick', 'role' => 'Full-stack engineer', 'bio' => 'Full-stack engineer turned entrepreneur. 19 years building scalable web systems.', 'letter' => 'P', 'color' => 'from-indigo-500 to-indigo-700'],
                ['name' => 'Kallol Sam', 'role' => 'Head of AI Team', 'bio' => 'A highly adaptable, results-driven AI Head and Principal AI/ML Engineer specializing in end-to-end AI product lifecycles and scalable architecture.', 'letter' => 'K', 'color' => 'from-violet-500 to-violet-700'],
                ['name' => 'Sujay Bhattacharya', 'role' => 'Head of Backend Developing Team', 'bio' => 'Backend developer turned entrepreneur with nearly 19 years of experience in scalable web applications, database design, and software architecture.', 'letter' => 'S', 'color' => 'from-indigo-600 to-violet-600'],
                ['name' => 'Tridip Sarkar', 'role' => 'Head of IOS Team', 'bio' => 'Led mobile development teams at multiple unicorn startups. iOS and Android expert.', 'letter' => 'T', 'color' => 'from-violet-600 to-indigo-500'],['name' => 'Avisek Mal', 'role' => 'Head of Android Team', 'bio' => 'Led mobile development teams at multiple unicorn startups. iOS and Android expert.', 'letter' => 'A', 'color' => 'from-violet-600 to-indigo-500'],['name' => 'Pathikrit Pramanick', 'role' => 'Head of HR Team', 'bio' => 'An HR professional with technical expertise (like WordPress) acts as a Digital HR Leader.', 'letter' => 'P', 'color' => 'from-violet-600 to-indigo-500'],
            ];
            @endphp
            @foreach ($team as $member)
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="bg-gradient-to-br {{ $member['color'] }} h-32 flex items-center justify-center">
                    <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-3xl font-bold">
                        {{ $member['letter'] }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-slate-900 text-lg">{{ $member['name'] }}</h3>
                    <div class="text-indigo-600 text-sm font-medium mt-0.5 mb-3">{{ $member['role'] }}</div>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $member['bio'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-gradient-to-br from-indigo-600 to-violet-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-5">Ready to Work Together?</h2>
        <p class="text-indigo-100 text-xl mb-8">We'd love to hear about your project and discuss how we can help.</p>
        <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-white text-indigo-600 font-semibold hover:bg-indigo-50 transition-all shadow-lg hover:-translate-y-0.5">
            Get in Touch
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
