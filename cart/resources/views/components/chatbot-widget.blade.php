<!-- Floating Chatbot Widget -->
<div id="floating-chatbot" class="fixed bottom-4 right-4 z-[9999]">
    <!-- Chatbot Button (Minimized State) -->
    <div id="chatbot-btn" class="bg-blue-600 text-white rounded-full w-14 h-14 flex items-center justify-center cursor-pointer shadow-lg hover:bg-blue-700 transition-all duration-300 hover:scale-110">
        <i class="fas fa-comments text-xl"></i>
    </div>

    <!-- Chatbot Container (Expanded State) -->
    <div id="chatbot-container" class="hidden absolute bottom-16 right-0 bg-white rounded-lg shadow-2xl flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-robot text-lg"></i>
                <div>
                    <h3 class="font-bold text-sm">AI Assistant</h3>
                    <p class="text-xs text-blue-100">Online · Ready to help</p>
                </div>
            </div>
            <button id="chatbot-close" class="text-white hover:bg-blue-800 rounded-full p-1 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Messages Container -->
        <div id="chatbot-messages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
            <!-- Welcome Message -->
            <div class="flex gap-2 items-start">
                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-xs"></i>
                </div>
                <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs">
                    <p class="text-sm text-gray-700">Hello! I'm your AI assistant. How can I help you today? 👋</p>
                    <p class="text-xs text-gray-400 mt-1">You can chat or use voice commands</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="border-t border-gray-200 p-3 bg-white space-y-2">
            <!-- Text Input -->
            <div class="flex gap-2">
                <input 
                    type="text" 
                    id="chatbot-input" 
                    placeholder="Type your message..." 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm"
                />
                <button 
                    id="chatbot-send" 
                    class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>

            <!-- Audio Controls -->
            <div class="flex gap-2 items-center">
                <button 
                    id="chatbot-voice-start" 
                    class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium flex items-center justify-center gap-2"
                    title="Start voice recording"
                >
                    <i class="fas fa-microphone"></i>
                    <span>Voice</span>
                </button>
                <button 
                    id="chatbot-voice-stop" 
                    class="hidden flex-1 bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition text-sm font-medium flex items-center justify-center gap-2"
                    title="Stop voice recording"
                >
                    <i class="fas fa-stop-circle"></i>
                    <span>Stop</span>
                </button>
                <button 
                    id="chatbot-volume" 
                    class="bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 transition text-sm"
                    title="Toggle speaker"
                >
                    <i class="fas fa-volume-up"></i>
                </button>
            </div>

            <!-- Voice Status -->
            <div id="voice-status" class="hidden text-center text-xs text-gray-600 py-1 bg-gray-100 rounded">
                <i class="fas fa-circle text-red-500 animate-pulse"></i> Recording...
            </div>
        </div>

        <!-- Quick Actions Footer -->
        <div class="border-t border-gray-200 p-3 bg-gray-50 flex gap-2 text-xs">
            <button class="flex-1 text-gray-700 hover:bg-gray-200 py-1 px-2 rounded transition text-center" onclick="chatbotQuickAction('Order status')">
                Order Status
            </button>
            <button class="flex-1 text-gray-700 hover:bg-gray-200 py-1 px-2 rounded transition text-center" onclick="chatbotQuickAction('Help')">
                Help
            </button>
            <button class="flex-1 text-gray-700 hover:bg-gray-200 py-1 px-2 rounded transition text-center" onclick="chatbotQuickAction('Support')">
                Support
            </button>
        </div>
    </div>
</div>

<style>
    #floating-chatbot {
        position: fixed;
        right: 16px;
        bottom: 16px;
        z-index: 9999;
    }

    #chatbot-btn {
        width: 56px;
        height: 56px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    #chatbot-container {
        position: fixed;
        right: 16px;
        bottom: 88px;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 0.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        width: min(24rem, calc(100vw - 1.5rem));
        height: min(600px, calc(100vh - 6rem));
        max-height: calc(100vh - 6rem);
    }

    #chatbot-container.hidden {
        display: none !important;
    }

    #chatbot-messages {
        flex: 1 1 auto;
        overflow-y: auto;
    }

    @media (max-width: 640px) {
        #floating-chatbot {
            right: 0.5rem;
            bottom: 0.5rem;
        }

        #chatbot-container {
            right: 0;
            bottom: 80px;
            width: calc(100vw - 1rem);
            height: min(560px, calc(100vh - 5.5rem));
            max-height: calc(100vh - 5.5rem);
        }
    }

    @keyframes bounce-in {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
        }
    }

    #chatbot-btn {
        animation: bounce-in 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    #chatbot-btn:hover {
        animation: pulse-ring 2s infinite;
    }

    #chatbot-messages {
        scroll-behavior: smooth;
    }

    .message-user {
        display: flex;
        justify-content: flex-end;
    }

    .message-user .message-content {
        background-color: #2563eb;
        color: white;
        border-radius: 1rem;
    }

    .message-bot .message-content {
        background-color: white;
        color: #374151;
        border-radius: 1rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .voice-active {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
</style>

<script src="{{ asset('js/chatbot.js') }}"></script>
