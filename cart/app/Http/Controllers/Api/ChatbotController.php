<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\ChatbotService;

class ChatbotController extends Controller
{
    public function getFAQs(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string',
        ]);

        $faqs = ChatbotService::getFAQsList($request->get('category'));

        return response()->json([
            'faqs' => $faqs,
            'categories' => ChatbotService::getFAQCategories(),
        ]);
    }

    public function searchFAQs(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $results = ChatbotService::searchFAQs($request->get('q'));

        return response()->json(['results' => $results]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        try {
            // Generate a unique session ID
            $sessionId = \Illuminate\Support\Str::uuid();
            
            // Attempt to get authenticated user (optional)
            $user = null;

            // Process the message
            $chatbotService = new ChatbotService($user, $sessionId);
            $response = $chatbotService->processMessage($request->message);

            return response()->json([
                'reply' => $response['response'] ?? 'I understand your message.',
                'message' => $response['response'] ?? 'I understand your message.',
                'faq_id' => $response['faq_id'] ?? null,
                'requires_escalation' => $response['requires_escalation'] ?? false,
                'session_id' => $sessionId,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Chatbot message error: ' . $e->getMessage(), [
                'exception' => class_basename($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'reply' => 'I apologize, but I encountered an error processing your request. Please try again later or contact support.',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ]);
        }
    }

    public function getConversationHistory(Request $request)
    {
        try {
            $sessionId = \Illuminate\Support\Str::uuid();
            $user = null;

            $chatbotService = new ChatbotService($user, $sessionId);
            $history = $chatbotService->getConversationHistory();

            return response()->json($history);
        } catch (\Throwable $e) {
            \Log::error('Chatbot history error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function escalateToSupport(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:5|max:500',
        ]);

        try {
            $sessionId = \Illuminate\Support\Str::uuid();
            $user = null;

            $chatbotService = new ChatbotService($user, $sessionId);
            $chatbotService->escalateToSupport($request->reason);

            return response()->json([
                'message' => 'Your issue has been escalated to our support team. We will contact you soon.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Chatbot escalation error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
