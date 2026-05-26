@extends('layouts.app')

@section('title', 'WebsolAI — Web & Mobile App Development Agency')
@section('meta_description', 'WebsolAI builds high-performance websites and mobile apps. Partner with us to bring your digital vision to life.')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-white min-h-[92vh] flex items-center">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-60 -right-60 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-60 -left-60 w-96 h-96 bg-violet-600/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-indigo-800/10 rounded-full blur-3xl"></div>
        {{-- Grid pattern --}}
        <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(99,102,241,0.08) 1px, transparent 1px); background-size: 40px 40px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600/20 border border-indigo-500/30 text-indigo-300 text-sm font-medium mb-8">
                <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                Web & Mobile App Development Agency
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold leading-[1.1] tracking-tight mb-6">
                Building <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Digital</span><br>
                Solutions<br>That Matter
            </h1>

            <p class="text-xl text-slate-300 leading-relaxed mb-10 max-w-2xl">
                We design and develop exceptional websites and mobile applications that help businesses connect with customers, automate operations, and achieve lasting growth.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ url('/contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-600/30 hover:shadow-indigo-500/40 hover:-translate-y-0.5">
                    Start Your Project
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="{{ url('/services') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl border border-slate-600 text-slate-200 font-semibold hover:bg-white/10 hover:border-slate-400 transition-all">
                    Explore Services
                </a>
            </div>

            {{-- Stats --}}
            <div class="flex flex-wrap gap-10 mt-16 pt-16 border-t border-slate-700/60">
                <div>
                    <div class="text-3xl font-bold text-white">150+</div>
                    <div class="text-sm text-slate-400 mt-1">Projects Delivered</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white">98%</div>
                    <div class="text-sm text-slate-400 mt-1">Client Satisfaction</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white">5+</div>
                    <div class="text-sm text-slate-400 mt-1">Years Experience</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white">30+</div>
                    <div class="text-sm text-slate-400 mt-1">Team Members</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Overview --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">What We Do</span>
            <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-4">Services Built for Your Success</h2>
            <p class="text-slate-500 text-lg leading-relaxed">From ideation to deployment, we deliver end-to-end digital solutions tailored to your business goals.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $services = [
                ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Web Development', 'desc' => 'Custom websites and web applications built with modern technologies. Fast, secure, and scalable solutions that convert visitors into customers.'],
                ['icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'Mobile App Development', 'desc' => 'Native and cross-platform mobile apps for iOS and Android. Engaging user experiences that keep customers coming back.'],
                ['icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'title' => 'UI/UX Design', 'desc' => 'Beautiful, intuitive designs that delight users. Research-driven design that aligns visual appeal with business outcomes.'],
                ['icon' => 'M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'API Development & Integration', 'desc' => 'Seamless third-party integrations and custom API development to connect your systems and automate critical workflows.'],
                ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'E-Commerce Solutions', 'desc' => 'Full-featured online stores with secure payment gateways, inventory management, and powerful analytics dashboards.'],
                ['icon' => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z', 'title' => 'Cloud & DevOps', 'desc' => 'Scalable cloud infrastructure, CI/CD pipelines, and DevOps practices ensuring your applications stay reliable and performant.'],
            ];
            @endphp

            @foreach ($services as $service)
            <div class="group p-8 rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-50 hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-indigo-50 flex items-center justify-center mb-6 group-hover:bg-indigo-600 transition-colors duration-300">
                    <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $service['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $service['title'] }}</h3>
                <p class="text-slate-500 leading-relaxed text-sm">{{ $service['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ url('/services') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-indigo-200 text-indigo-600 font-semibold hover:bg-indigo-50 transition-all">
                View All Services
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- Why Choose Us --}}
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">Why WebsolAI</span>
                <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-6">We Turn Ideas Into Powerful Digital Realities</h2>
                <p class="text-slate-500 text-lg leading-relaxed mb-10">
                    We combine deep technical expertise with creative thinking to build products that don't just look great — they perform exceptionally. Our client-first approach means we're always aligned with your business goals.
                </p>

                <div class="space-y-6">
                    @php
                    $reasons = [
                        ['title' => 'Expert Team', 'desc' => 'Seasoned developers, designers, and strategists with deep industry experience across 20+ sectors.'],
                        ['title' => 'Transparent Process', 'desc' => 'Clear timelines, regular updates, and open communication throughout every stage of your project.'],
                        ['title' => 'On-Time Delivery', 'desc' => 'We respect your deadlines and consistently deliver high-quality products on schedule.'],
                        ['title' => 'Post-Launch Support', 'desc' => 'Comprehensive maintenance and support packages to keep your digital products running flawlessly.'],
                    ];
                    @endphp
                    @foreach ($reasons as $reason)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-900">{{ $reason['title'] }}</h4>
                            <p class="text-slate-500 text-sm mt-1 leading-relaxed">{{ $reason['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">150+</div>
                    <div class="text-slate-700 font-semibold">Projects Completed</div>
                    <div class="text-slate-400 text-sm mt-1">Across 20+ industries</div>
                </div>
                <div class="bg-indigo-600 rounded-2xl p-7 text-white mt-6">
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="font-semibold">Client Satisfaction</div>
                    <div class="text-indigo-200 text-sm mt-1">Based on 120+ reviews</div>
                </div>
                <div class="bg-slate-900 rounded-2xl p-7 text-white -mt-6">
                    <div class="text-4xl font-bold mb-2">5+</div>
                    <div class="font-semibold">Years in Business</div>
                    <div class="text-slate-400 text-sm mt-1">Trusted since 2019</div>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">30+</div>
                    <div class="text-slate-700 font-semibold">Team Members</div>
                    <div class="text-slate-400 text-sm mt-1">Across disciplines</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Process --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-widest">How We Work</span>
            <h2 class="text-4xl font-bold text-slate-900 mt-3 mb-4">Our Proven Development Process</h2>
            <p class="text-slate-500 text-lg leading-relaxed">A structured approach that ensures every project is delivered on time, within budget, and to the highest quality standards.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
            $steps = [
                ['n' => '1', 'title' => 'Discovery', 'desc' => 'We learn about your business, goals, and target audience to define the perfect solution strategy.'],
                ['n' => '2', 'title' => 'Design', 'desc' => 'Our designers craft beautiful wireframes and interactive prototypes, bringing your vision to life.'],
                ['n' => '3', 'title' => 'Development', 'desc' => 'Expert engineers build your product using modern, scalable technologies and clean coding practices.'],
                ['n' => '4', 'title' => 'Launch & Support', 'desc' => 'We deploy your product and provide ongoing support to ensure continued success and growth.'],
            ];
            @endphp
            @foreach ($steps as $step)
            <div class="relative text-center group">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center mx-auto mb-6 shadow-lg shadow-indigo-200 group-hover:shadow-indigo-300 group-hover:-translate-y-1 transition-all">
                    <span class="text-white text-2xl font-bold">{{ $step['n'] }}</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $step['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="py-24 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-indigo-400 font-semibold text-sm uppercase tracking-widest">Client Stories</span>
            <h2 class="text-4xl font-bold text-white mt-3 mb-4">What Our Clients Say</h2>
            <p class="text-slate-400 text-lg">Real feedback from businesses we've helped grow in the digital world.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
            $testimonials = [
                ['quote' => '"WebsolAI transformed our outdated website into a modern, high-performing platform. Our online leads increased by 240% within the first three months. Absolutely outstanding work."', 'name' => 'Sarah Mitchell', 'role' => 'CEO, TechRetail Co.', 'letter' => 'S', 'color' => 'bg-indigo-600'],
                ['quote' => '"The mobile app they built for us exceeded every expectation. The team communicated brilliantly throughout, delivered on time, and the end product is beautiful and intuitive."', 'name' => 'James Okafor', 'role' => 'Founder, FitTrack App', 'letter' => 'J', 'color' => 'bg-violet-600'],
                ['quote' => '"Professional, responsive, and incredibly talented. WebsolAI built our e-commerce platform from scratch and it has been running flawlessly. Best investment we have made."', 'name' => 'Amara Chen', 'role' => 'Director, StyleHouse', 'letter' => 'A', 'color' => 'bg-emerald-600'],
            ];
            @endphp
            @foreach ($testimonials as $t)
            <div class="bg-slate-800 rounded-2xl p-8 flex flex-col">
                <div class="flex gap-1 mb-6">
                    @for ($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-slate-300 leading-relaxed mb-8 flex-grow">{{ $t['quote'] }}</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full {{ $t['color'] }} flex items-center justify-center text-white font-bold text-sm">{{ $t['letter'] }}</div>
                    <div>
                        <div class="font-semibold text-white text-sm">{{ $t['name'] }}</div>
                        <div class="text-slate-400 text-xs mt-0.5">{{ $t['role'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-24 bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6 leading-tight">Ready to Build Something Amazing?</h2>
        <p class="text-indigo-100 text-xl mb-10 max-w-2xl mx-auto leading-relaxed">Let's discuss your project and explore how we can help you achieve your digital goals. No obligation — just a friendly conversation.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-white text-indigo-600 font-semibold hover:bg-indigo-50 transition-all shadow-lg hover:-translate-y-0.5">
                Get a Free Consultation
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ url('/services') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl border-2 border-white/40 text-white font-semibold hover:bg-white/10 transition-all">
                Our Services
            </a>
        </div>
    </div>
</section>

@endsection
