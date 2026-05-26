@extends('layouts.app')

@section('title', 'Terms of Service — WebsolAI')
@section('meta_description', 'Read WebsolAI\'s Terms of Service governing the use of our website and the services we provide.')

@section('content')

<section class="bg-gradient-to-br from-slate-950 to-indigo-950 text-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-indigo-400 font-semibold text-sm uppercase tracking-widest">Legal</span>
        <h1 class="text-4xl sm:text-5xl font-bold mt-4 mb-4">Terms of Service</h1>
        <p class="text-slate-300">Last updated: {{ date('F d, Y') }}</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-10">
            <p class="text-slate-700 text-sm leading-relaxed m-0">
                <strong>Please read these Terms of Service carefully before using our website or engaging our services.</strong> By accessing our website or entering into a service agreement with WebsolAI, you agree to be bound by these terms. If you do not agree, please do not use our website or services.
            </p>
        </div>

        @php
        $sections = [
            [
                'title' => '1. Definitions',
                'content' => '<p>In these Terms:</p><ul><li><strong>"WebsolAI", "we", "us", "our"</strong> refers to WebsolAI Ltd., registered in the UK.</li><li><strong>"Client", "you", "your"</strong> refers to any individual or entity that uses our website or engages our services.</li><li><strong>"Services"</strong> means web development, mobile app development, UI/UX design, and any other digital services we provide.</li><li><strong>"Website"</strong> means websolai.com and all associated subdomains.</li><li><strong>"Project"</strong> means any specific piece of work agreed between WebsolAI and a Client.</li></ul>',
            ],
            [
                'title' => '2. Acceptance of Terms',
                'content' => '<p>By accessing our website or engaging our services, you confirm that:</p><ul><li>You are at least 18 years of age.</li><li>You have the legal authority to enter into this agreement on behalf of yourself or the organisation you represent.</li><li>You have read, understood, and agree to be bound by these Terms.</li></ul><p>These Terms apply to all visitors, users, and clients of WebsolAI.</p>',
            ],
            [
                'title' => '3. Services',
                'content' => '<p>WebsolAI provides web and mobile application development, UI/UX design, API development, e-commerce solutions, cloud services, and related digital services. The specific scope, deliverables, timeline, and cost of each project will be defined in a separate Statement of Work (SOW) or project agreement.</p><p>We reserve the right to refuse service to anyone for any reason at any time. We may also change, suspend, or discontinue any aspect of our services with reasonable notice.</p>',
            ],
            [
                'title' => '4. Project Agreements & Statements of Work',
                'content' => '<p>All project engagements will be governed by a signed Statement of Work (SOW) or client agreement that includes:</p><ul><li>Project scope and deliverables</li><li>Timeline and milestones</li><li>Payment schedule and amounts</li><li>Revision and approval processes</li><li>Intellectual property provisions</li></ul><p>In the event of a conflict between these Terms and a signed SOW, the SOW shall take precedence.</p>',
            ],
            [
                'title' => '5. Payment Terms',
                'content' => '<p>Unless otherwise agreed in writing:</p><ul><li>A deposit of 30–50% is required before project commencement.</li><li>Milestone payments are due within 14 days of invoice.</li><li>Final payment is due before final deliverables are released or deployed.</li></ul><p>Overdue invoices may incur late payment interest at 8% per annum above the Bank of England base rate, in accordance with the Late Payment of Commercial Debts Act 1998. We reserve the right to suspend work on a project where payment is overdue.</p>',
            ],
            [
                'title' => '6. Revisions & Change Requests',
                'content' => '<p>Each project includes a defined number of revision rounds as specified in the SOW. Additional revisions or changes to scope will be quoted separately and may affect the project timeline.</p><p>Change requests must be submitted in writing. We will provide a written estimate for any additional cost or time before proceeding with out-of-scope work.</p>',
            ],
            [
                'title' => '7. Client Responsibilities',
                'content' => '<p>To ensure the successful and timely delivery of your project, you agree to:</p><ul><li>Provide accurate, complete, and timely information, content, and feedback.</li><li>Appoint a designated point of contact with decision-making authority.</li><li>Respond to requests for feedback within the agreed timeframe (typically 5 business days).</li><li>Ensure all content and materials you provide do not infringe any third-party rights.</li></ul><p>Delays caused by the Client\'s failure to meet these responsibilities may result in revised timelines and additional charges.</p>',
            ],
            [
                'title' => '8. Intellectual Property',
                'content' => '<p><strong>Client Content:</strong> You retain ownership of all content, data, and materials you provide to us. You grant us a limited licence to use this content solely for the purpose of delivering the agreed services.</p><p><strong>Project Deliverables:</strong> Upon receipt of full payment, all project deliverables and custom-created work will be assigned to the Client, except for any pre-existing WebsolAI tools, libraries, or frameworks used in the project (which remain our property and are licensed to you for use within the delivered project).</p><p><strong>Portfolio Rights:</strong> Unless agreed otherwise in writing, we reserve the right to showcase completed projects in our portfolio and marketing materials.</p>',
            ],
            [
                'title' => '9. Confidentiality',
                'content' => '<p>Both parties agree to keep confidential any proprietary or sensitive information disclosed during the project. This obligation continues for two years after project completion.</p><p>This obligation does not apply to information that is publicly available, independently developed, or required to be disclosed by law.</p>',
            ],
            [
                'title' => '10. Warranties & Representations',
                'content' => '<p>We warrant that:</p><ul><li>We have the right to enter into this agreement.</li><li>Services will be performed with reasonable skill and care.</li><li>Deliverables will materially conform to the agreed specifications.</li></ul><p>We do not warrant that:</p><ul><li>Our services will meet every business objective you have in mind.</li><li>The website will be free of errors at all times after delivery.</li><li>Third-party services or platforms we integrate will remain available or unchanged.</li></ul>',
            ],
            [
                'title' => '11. Limitation of Liability',
                'content' => '<p>To the fullest extent permitted by law, WebsolAI\'s total liability to you for any claim arising from or related to our services shall not exceed the total fees paid by you for the specific project giving rise to the claim.</p><p>We shall not be liable for:</p><ul><li>Indirect, incidental, or consequential damages</li><li>Loss of revenue, profits, or business opportunities</li><li>Data loss (beyond our reasonable control)</li><li>Third-party actions or service failures</li></ul>',
            ],
            [
                'title' => '12. Termination',
                'content' => '<p>Either party may terminate an engagement by providing 30 days written notice, subject to the following:</p><ul><li>The Client shall pay for all work completed up to the termination date, plus any non-recoverable costs.</li><li>Upon termination, each party shall return or destroy confidential information belonging to the other party.</li></ul><p>We may terminate immediately in the event of non-payment, breach of these Terms, or conduct that is harmful, illegal, or unethical.</p>',
            ],
            [
                'title' => '13. Website Use',
                'content' => '<p>When using our website, you agree not to:</p><ul><li>Use the website in any way that violates applicable law or regulation.</li><li>Transmit any unsolicited or unauthorised advertising material (spam).</li><li>Attempt to gain unauthorised access to any part of the website or its systems.</li><li>Interfere with or disrupt the operation of the website.</li><li>Scrape, crawl, or systematically collect data from the website without our consent.</li></ul>',
            ],
            [
                'title' => '14. Third-Party Links & Services',
                'content' => '<p>Our website may contain links to third-party websites. We are not responsible for the content, privacy practices, or terms of any third-party website. Links are provided for convenience only and do not imply endorsement.</p>',
            ],
            [
                'title' => '15. Governing Law & Dispute Resolution',
                'content' => '<p>These Terms are governed by the laws of England and Wales. Any dispute arising from or in connection with these Terms shall first be subject to good-faith negotiation between the parties.</p><p>If a dispute cannot be resolved through negotiation within 30 days, either party may refer the matter to mediation. If mediation is unsuccessful, disputes shall be subject to the exclusive jurisdiction of the courts of England and Wales.</p>',
            ],
            [
                'title' => '16. Changes to These Terms',
                'content' => '<p>We may update these Terms from time to time. The "Last updated" date at the top of this page reflects the most recent revision. Continued use of our website or services after changes are posted constitutes your acceptance of the revised Terms.</p><p>For significant changes, we will endeavour to notify active clients by email.</p>',
            ],
            [
                'title' => '17. Contact',
                'content' => '<p>If you have any questions about these Terms, please contact us:</p><ul><li><strong>Post:</strong> WebsolAI, Sonarpur, Kolkata, West Bengal, India</li><li><strong>Contact Form:</strong> <a href="/contact" class="text-indigo-600 hover:underline">websolai.com/contact</a></li></ul>',
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

        <div class="mt-12 p-6 bg-slate-50 rounded-2xl border border-slate-200">
            <p class="text-sm text-slate-500 leading-relaxed">
                These Terms of Service are provided for informational purposes. They do not constitute legal advice. If you have specific legal concerns, we recommend seeking independent legal counsel.
            </p>
        </div>

    </div>
</section>

@endsection
