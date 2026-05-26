<?php

namespace App\Http\Controllers\Web;

use App\Models\Content\Page;
use Illuminate\Routing\Controller;

class PageController extends Controller
{
    /**
     * Display the specified page.
     */
    public function show(Page $page)
    {
        // Check if page is active
        if (!$page->is_active) {
            abort(404, 'Page not found');
        }

        return view('frontend.pages.show', compact('page'));
    }
}
