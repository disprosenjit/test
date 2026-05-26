@extends('layouts.app')

@section('title', 'Contact Us — WebsolAI')
@section('meta_description', 'Get in touch with WebsolAI. Tell us about your project and we\'ll get back to you within one business day.')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-slate-950 to-indigo-950 text-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-indigo-400 font-semibold text-sm uppercase tracking-widest">Get in Touch</span>
        <h1 class="text-5xl sm:text-6xl font-bold mt-4 mb-6 leading-tight">
            Let's Start a <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Conversation</span>
        </h1>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Have a project in mind? We'd love to hear about it. Fill in the form and we'll get back to you within one business day.
        </p>
    </div>
</section>

{{-- Contact Section --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-16">

            {{-- Contact Info --}}
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold text-slate-900 mb-8">Contact Information</h2>

                <div class="space-y-8">
                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900 mb-1">Office</div>
                            <p class="text-slate-600">WebsolAI</p>
                            <p class="text-slate-600">Sonarpur, Kolkata</p>
                            <p class="text-slate-600">West Bengal, India</p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900 mb-1">Business Hours</div>
                            <p class="text-slate-600 text-sm">Monday – Friday: 9:00 AM – 6:00 PM (GMT)</p>
                            <p class="text-slate-500 text-sm mt-1">Weekend support available for urgent issues.</p>
                        </div>
                    </div>
                </div>

                {{-- FAQ --}}
                <div class="mt-12 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <h3 class="font-bold text-slate-900 mb-4">Common Questions</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <div class="font-medium text-slate-700 mb-1">How long does a project take?</div>
                            <div class="text-slate-500">Websites typically take 4–8 weeks. Mobile apps 8–16 weeks depending on complexity.</div>
                        </div>
                        <div>
                            <div class="font-medium text-slate-700 mb-1">Do you offer fixed-price projects?</div>
                            <div class="text-slate-500">Yes. We offer both fixed-price and time-and-materials engagements.</div>
                        </div>
                        <div>
                            <div class="font-medium text-slate-700 mb-1">What happens after launch?</div>
                            <div class="text-slate-500">We offer flexible maintenance and support packages to keep your product healthy.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 lg:p-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Send Us a Message</h2>
                    <p class="text-slate-500 mb-8">Tell us about your project and we'll get back to you with a plan.</p>

                    @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-emerald-700 text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    @endif

                    <form action="{{ url('/contact') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-slate-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" required autocomplete="given-name"
                                    value="{{ old('first_name') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('first_name') border-red-400 @enderror"
                                    placeholder="John">
                                @error('first_name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-slate-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" required autocomplete="family-name"
                                    value="{{ old('last_name') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('last_name') border-red-400 @enderror"
                                    placeholder="Smith">
                                @error('last_name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email"
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-400 @enderror"
                                placeholder="john@example.com">
                            @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" autocomplete="tel"
                                value="{{ old('phone') }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                placeholder="+1 (555) 000-0000">
                        </div>

                        <div>
                            <label for="service" class="block text-sm font-medium text-slate-700 mb-2">Service Interested In <span class="text-red-500">*</span></label>
                            <select id="service" name="service" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all bg-white @error('service') border-red-400 @enderror">
                                <option value="" disabled {{ old('service') ? '' : 'selected' }}>Select a service...</option>
                                <option value="web" {{ old('service') == 'web' ? 'selected' : '' }}>Web Development</option>
                                <option value="mobile" {{ old('service') == 'mobile' ? 'selected' : '' }}>Mobile App Development</option>
                                <option value="design" {{ old('service') == 'design' ? 'selected' : '' }}>UI/UX Design</option>
                                <option value="ecommerce" {{ old('service') == 'ecommerce' ? 'selected' : '' }}>E-Commerce Solution</option>
                                <option value="api" {{ old('service') == 'api' ? 'selected' : '' }}>API Development & Integration</option>
                                <option value="cloud" {{ old('service') == 'cloud' ? 'selected' : '' }}>Cloud & DevOps</option>
                                <option value="other" {{ old('service') == 'other' ? 'selected' : '' }}>Other / Not Sure</option>
                            </select>
                            @error('service')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="budget" class="block text-sm font-medium text-slate-700 mb-2">Estimated Budget</label>
                            <select id="budget" name="budget"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all bg-white">
                                <option value="" disabled selected>Select a range...</option>
                                <option value="under-5k">Under $5,000</option>
                                <option value="5k-15k">$5,000 – $15,000</option>
                                <option value="15k-30k">$15,000 – $30,000</option>
                                <option value="30k-50k">$30,000 – $50,000</option>
                                <option value="over-50k">Over $50,000</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700 mb-2">Project Details <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" required rows="5"
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none @error('message') border-red-400 @enderror"
                                placeholder="Tell us about your project — what you're building, your goals, and any key requirements...">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="consent" name="consent" required class="mt-1 w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="consent" class="text-sm text-slate-500">
                                I agree to the <a href="{{ url('/privacy') }}" class="text-indigo-600 hover:underline">Privacy Policy</a> and consent to WebsolAI contacting me regarding my enquiry.
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition-all shadow-sm hover:-translate-y-0.5 hover:shadow-md">
                            Send Message
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
