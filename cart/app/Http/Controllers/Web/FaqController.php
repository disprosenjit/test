<?php

namespace App\Http\Controllers\Web;

use App\Models\Content\ChatbotFaq;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FaqController extends Controller
{
    /**
     * Display FAQs listing page
     */
    public function index(Request $request)
    {
        $query = ChatbotFaq::active();

        // Search by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Search by keyword
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereRaw("MATCH(question, answer, keywords) AGAINST(? IN BOOLEAN MODE)", ["%{$request->search}%"])
                  ->orWhere('question', 'like', "%{$request->search}%")
                  ->orWhere('answer', 'like', "%{$request->search}%")
                  ->orWhere('keywords', 'like', "%{$request->search}%");
            });
        }

        $faqs = $query->orderBy('display_order')->orderBy('id')->paginate(10);
        $categories = ChatbotFaq::active()->select('category')->distinct()->pluck('category');

        return view('frontend.faqs.index', compact('faqs', 'categories'));
    }

    /**
     * Display single FAQ detail
     */
    public function show(ChatbotFaq $faq)
    {
        if (!$faq->is_active) {
            abort(404, 'FAQ not found');
        }

        // Increment view count
        $faq->incrementViewCount();

        $relatedFaqs = ChatbotFaq::active()
            ->where('category', $faq->category)
            ->where('id', '!=', $faq->id)
            ->limit(5)
            ->get();

        return view('frontend.faqs.show', compact('faq', 'relatedFaqs'));
    }
}
