@extends('layouts.app')

@section('title', 'Privacy Policy — WebsolAI')
@section('meta_description', 'Read WebsolAI\'s privacy policy to understand how we collect, use, and protect your personal information.')

@section('content')

<section class="bg-gradient-to-br from-slate-950 to-indigo-950 text-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-indigo-400 font-semibold text-sm uppercase tracking-widest">Legal</span>
        <h1 class="text-4xl sm:text-5xl font-bold mt-4 mb-4">Privacy Policy</h1>
        <p class="text-slate-300">Last updated: {{ date('F d, Y') }}</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-slate max-w-none">

            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-10">
                <p class="text-slate-700 text-sm leading-relaxed m-0">
                    At WebsolAI, your privacy is a priority. This Privacy Policy explains what information we collect, how we use it, how we protect it, and your rights regarding your data. By using our website and services, you agree to the practices described in this policy.
                </p>
            </div>

            @php
            $sections = [
                [
                    'title' => '1. Who We Are',
                    'content' => '<p>WebsolAI ("we", "us", "our") is a web and mobile application development agency. Our address is WebsolAI, Sonarpur, Kolkata, West Bengal, India.</p><p>For questions about this privacy policy or how we handle your data, please use the contact form on our website.</p>',
                ],
                [
                    'title' => '2. Information We Collect',
                    'content' => '<p>We collect information in the following ways:</p><p><strong>Information you provide directly:</strong></p><ul><li>Contact form submissions (name, email, phone number, project details)</li><li>Email correspondence</li><li>Information provided when requesting a quote or consultation</li></ul><p><strong>Information collected automatically:</strong></p><ul><li>IP address and browser type</li><li>Pages visited and time spent on our website</li><li>Referring URLs and device information</li><li>Cookies and similar tracking technologies (see Section 7)</li></ul>',
                ],
                [
                    'title' => '3. How We Use Your Information',
                    'content' => '<p>We use your personal information to:</p><ul><li>Respond to your enquiries and provide requested services</li><li>Send project-related communications and updates</li><li>Improve our website and services based on usage patterns</li><li>Send relevant marketing communications (only with your consent)</li><li>Comply with legal obligations</li><li>Detect and prevent fraud and abuse</li></ul><p>We will never sell your personal data to third parties.</p>',
                ],
                [
                    'title' => '4. Legal Basis for Processing',
                    'content' => '<p>We process your personal data under the following legal bases (in accordance with the UK GDPR):</p><ul><li><strong>Consent:</strong> Where you have explicitly agreed, such as signing up for our newsletter.</li><li><strong>Contractual necessity:</strong> When processing is necessary to fulfil a contract or respond to a service request.</li><li><strong>Legitimate interests:</strong> To improve our services and communicate with prospective clients, where these interests are not overridden by your rights.</li><li><strong>Legal obligation:</strong> Where we must process data to comply with applicable law.</li></ul>',
                ],
                [
                    'title' => '5. Data Sharing & Third Parties',
                    'content' => '<p>We do not sell, trade, or rent your personal information. We may share data with:</p><ul><li><strong>Service providers:</strong> Trusted third-party tools that help us operate (e.g., email services, analytics platforms). These providers are contractually obligated to handle your data securely.</li><li><strong>Legal authorities:</strong> Where required by law, court order, or regulatory authority.</li><li><strong>Business transfers:</strong> In the event of a merger or acquisition, your data may be transferred as part of that transaction, subject to equivalent privacy protections.</li></ul>',
                ],
                [
                    'title' => '6. Data Retention',
                    'content' => '<p>We retain personal data only for as long as necessary to fulfil the purposes outlined in this policy, or as required by law. Specifically:</p><ul><li>Contact enquiries: retained for up to 2 years</li><li>Client project data: retained for up to 7 years for tax and legal compliance</li><li>Marketing preferences: until you withdraw consent</li></ul><p>Upon request, we will delete your personal data sooner, subject to any overriding legal obligations.</p>',
                ],
                [
                    'title' => '7. Cookies',
                    'content' => '<p>Our website uses cookies to enhance your browsing experience. Cookies are small text files stored on your device. We use:</p><ul><li><strong>Essential cookies:</strong> Required for the website to function properly. These cannot be disabled.</li><li><strong>Analytics cookies:</strong> Help us understand how visitors interact with our site (e.g., Google Analytics). Used only with your consent.</li><li><strong>Preference cookies:</strong> Remember your settings and preferences.</li></ul><p>You can control cookies through your browser settings. Please note that disabling certain cookies may affect website functionality.</p>',
                ],
                [
                    'title' => '8. Your Rights',
                    'content' => '<p>Under applicable data protection law, you have the following rights:</p><ul><li><strong>Right of access:</strong> Request a copy of the personal data we hold about you.</li><li><strong>Right to rectification:</strong> Request correction of inaccurate or incomplete data.</li><li><strong>Right to erasure:</strong> Request deletion of your personal data ("right to be forgotten"), where applicable.</li><li><strong>Right to restrict processing:</strong> Request that we limit how we use your data.</li><li><strong>Right to data portability:</strong> Receive your data in a structured, machine-readable format.</li><li><strong>Right to object:</strong> Object to processing based on legitimate interests or for direct marketing.</li><li><strong>Right to withdraw consent:</strong> Withdraw consent at any time where processing is consent-based.</li></ul><p>To exercise any of these rights, email us at <a href="mailto:privacy@websolai.com" class="text-indigo-600 hover:underline">privacy@websolai.com</a>. We will respond within 30 days.</p>',
                ],
                [
                    'title' => '9. Data Security',
                    'content' => '<p>We implement appropriate technical and organisational measures to protect your personal data against unauthorised access, disclosure, alteration, or destruction. These include:</p><ul><li>Encrypted data transmission (SSL/TLS)</li><li>Restricted access controls</li><li>Regular security audits</li><li>Staff training on data protection</li></ul><p>However, no method of transmission over the internet is completely secure. While we strive to protect your data, we cannot guarantee absolute security.</p>',
                ],
                [
                    'title' => '10. International Data Transfers',
                    'content' => '<p>Where we transfer personal data outside the UK or EEA, we ensure appropriate safeguards are in place — such as Standard Contractual Clauses or equivalent mechanisms — to ensure your data receives adequate protection.</p>',
                ],
                [
                    'title' => '11. Children\'s Privacy',
                    'content' => '<p>Our website and services are not directed at individuals under the age of 16. We do not knowingly collect personal data from children. If you believe we have inadvertently collected information from a child, please contact us immediately and we will delete it.</p>',
                ],
                [
                    'title' => '12. Changes to This Policy',
                    'content' => '<p>We may update this Privacy Policy from time to time. When we make significant changes, we will update the "Last updated" date at the top of this page and, where appropriate, notify you via email. We encourage you to review this policy periodically.</p>',
                ],
                [
                    'title' => '13. Contact Us',
                    'content' => '<p>If you have any questions, concerns, or complaints about this Privacy Policy or our data practices, please contact us:</p><ul><li><strong>Post:</strong> WebsolAI, Sonarpur, Kolkata, West Bengal, India</li><li><strong>Contact Form:</strong> <a href="/contact" class="text-indigo-600 hover:underline">websolai.com/contact</a></li></ul>',
                ],
            ];
            @endphp

            <div class="space-y-10">
                @foreach ($sections as $section)
                <div class="pb-10 border-b border-slate-100 last:border-0 last:pb-0">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">{{ $section['title'] }}</h2>
                    <div class="text-slate-600 leading-relaxed space-y-4 [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:space-y-2 [&_li]:text-slate-600 [&_p]:text-slate-600 [&_strong]:text-slate-800">
                        {!! $section['content'] !!}
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

@endsection
