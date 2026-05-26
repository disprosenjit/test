<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content\ChatbotFaq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminChatbotFaqController extends Controller
{
    /**
     * Display all FAQs
     */
    public function index()
    {
        $faqs = ChatbotFaq::orderBy('display_order')->orderBy('id')->paginate(20);
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = ChatbotFaq::select('category')->distinct()->pluck('category');
        return view('admin.faqs.create', compact('categories'));
    }

    /**
     * Store new FAQ
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|max:100',
            'keywords' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        ChatbotFaq::create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return redirect('/admin/faqs')->with('success', 'FAQ created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(ChatbotFaq $faq)
    {
        $categories = ChatbotFaq::select('category')->distinct()->pluck('category');
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    /**
     * Update FAQ
     */
    public function update(Request $request, ChatbotFaq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|max:100',
            'keywords' => 'nullable|string',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $faq->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', $faq->is_active),
            'display_order' => $validated['display_order'] ?? 0,
        ]);

        return redirect('/admin/faqs')->with('success', 'FAQ updated successfully');
    }

    /**
     * Delete FAQ
     */
    public function destroy(ChatbotFaq $faq)
    {
        $faq->delete();
        return redirect('/admin/faqs')->with('success', 'FAQ deleted successfully');
    }
}
