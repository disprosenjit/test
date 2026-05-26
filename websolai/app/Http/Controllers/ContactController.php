<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email:rfc,dns', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'service'    => ['required', 'string', 'in:web,mobile,design,ecommerce,api,cloud,other'],
            'budget'     => ['nullable', 'string', 'in:under-5k,5k-15k,15k-30k,30k-50k,over-50k'],
            'message'    => ['required', 'string', 'min:20', 'max:5000'],
            'consent'    => ['accepted'],
        ]);

        Contact::create($validated);

        return redirect()->route('contact.form')
            ->with('success', 'Thank you for your message! We\'ll get back to you within one business day.');
    }
}

