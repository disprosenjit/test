<?php

namespace App\Services;

use App\Models\Content\ChatbotFaq;
use App\Models\Content\ChatbotConversation;
use App\Models\User\User;
use Illuminate\Support\Str;

class ChatbotService
{
    protected ?User $user;
    protected string $sessionId;
    protected ChatbotConversation $conversation;
    protected array $keywords = [];

    public function __construct(?User $user = null, ?string $sessionId = null)
    {
        $this->user = $user;
        $this->sessionId = $sessionId ?? Str::uuid();
        $this->initializeConversation();
        $this->loadKeywords();
    }

    /**
     * Initialize or get existing conversation
     */
    protected function initializeConversation(): void
    {
        $this->conversation = ChatbotConversation::where('session_id', $this->sessionId)
            ->where('status', 'active')
            ->first() ?? ChatbotConversation::create([
                'user_id' => $this->user?->id,
                'session_id' => $this->sessionId,
                'messages' => [],
                'status' => 'active',
            ]);
    }

    /**
     * Load all FAQ keywords for quick matching
     */
    protected function loadKeywords(): void
    {
        $faqs = ChatbotFaq::active()->get();
        foreach ($faqs as $faq) {
            $keywords = explode(',', $faq->keywords ?? '');
            $this->keywords[$faq->id] = [
                'question' => $faq->question,
                'answer' => $faq->answer,
                'keywords' => array_map('trim', $keywords),
            ];
        }
    }

    /**
     * Process user message and return bot response
     */
    public function processMessage(string $userMessage): array
    {
        // Add user message to conversation
        $this->conversation->addMessage('user', $userMessage);

        // Find matching FAQ or ask for clarification
        $botResponse = $this->findAnswer($userMessage);

        // Add bot response to conversation
        $this->conversation->addMessage('bot', $botResponse['answer']);

        return [
            'response' => $botResponse['answer'],
            'faq_id' => $botResponse['faq_id'] ?? null,
            'requires_escalation' => $botResponse['escalate'] ?? false,
            'session_id' => $this->sessionId,
        ];
    }

    /**
     * Find answer from FAQs using keyword matching
     */
    protected function findAnswer(string $userMessage): array
    {
        $userMessage = strtolower($userMessage);
        $scores = [];

        // Score each FAQ based on keyword matches
        foreach ($this->keywords as $faqId => $faqData) {
            $score = 0;

            // Check exact question match
            if (Str::contains($userMessage, strtolower($faqData['question']))) {
                $score += 10;
            }

            // Check keyword matches
            foreach ($faqData['keywords'] as $keyword) {
                if (!empty($keyword) && Str::contains($userMessage, strtolower($keyword))) {
                    $score += 5;
                }
            }

            if ($score > 0) {
                $scores[$faqId] = $score;
            }
        }

        // Return best match if score is high enough
        if (!empty($scores)) {
            arsort($scores);
            $topFaqId = array_key_first($scores);
            $topScore = reset($scores);

            if ($topScore >= 5) {
                return [
                    'faq_id' => $topFaqId,
                    'answer' => $this->keywords[$topFaqId]['answer'],
                    'escalate' => false,
                ];
            }
        }

        // No good match found - suggest escalation
        return [
            'answer' => $this->getDefaultResponse($userMessage),
            'escalate' => true,
            'faq_id' => null,
        ];
    }

    /**
     * Get default response when no FAQ matches
     */
    protected function getDefaultResponse(string $userMessage): string
    {
        $defaultResponses = [
            'Thank you for your question. I\'ll connect you with our support team who can provide more detailed assistance.',
            'I\'m not sure I understand your question completely. Our support team would be happy to help you better.',
            'Your question is important to us. Let me transfer you to a specialist who can assist you.',
        ];

        return $defaultResponses[array_rand($defaultResponses)];
    }

    /**
     * Escalate conversation to human support
     */
    public function escalateToSupport(string $reason = null): ChatbotConversation
    {
        $supportEmail = config('app.support_email', 'support@example.com');

        $this->conversation->escalate($supportEmail, $reason);

        // In production, send email/notification to support team
        // Mail::to($supportEmail)->send(new ChatbotEscalation($this->conversation));

        return $this->conversation;
    }

    /**
     * Get conversation history
     */
    public function getConversationHistory(): array
    {
        return [
            'session_id' => $this->sessionId,
            'messages' => $this->conversation->messages ?? [],
            'status' => $this->conversation->status,
            'escalated' => $this->conversation->escalated_to_support,
        ];
    }

    /**
     * Get FAQ list
     */
    public static function getFAQsList(?string $category = null)
    {
        $query = ChatbotFaq::active();

        if ($category) {
            $query->where('category', $category);
        }

        return $query->orderBy('display_order')->get();
    }

    /**
     * Get FAQ categories
     */
    public static function getFAQCategories()
    {
        return ChatbotFaq::active()
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    /**
     * Search FAQs
     */
    public static function searchFAQs(string $searchTerm)
    {
        $searchPattern = "%{$searchTerm}%";
        return ChatbotFaq::active()
            ->where(function ($query) use ($searchPattern) {
                $query->where('question', 'LIKE', $searchPattern)
                    ->orWhere('answer', 'LIKE', $searchPattern)
                    ->orWhere('keywords', 'LIKE', $searchPattern);
            })
            ->get();
    }

    /**
     * Close conversation
     */
    public function closeConversation(): ChatbotConversation
    {
        return $this->conversation->close();
    }
}
