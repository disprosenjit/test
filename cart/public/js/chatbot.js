// Chatbot Widget JavaScript
class ChatbotWidget {
    constructor() {
        this.chatbotBtn = document.getElementById('chatbot-btn');
        this.chatbotContainer = document.getElementById('chatbot-container');
        this.chatbotClose = document.getElementById('chatbot-close');
        this.chatbotInput = document.getElementById('chatbot-input');
        this.chatbotSend = document.getElementById('chatbot-send');
        this.chatbotMessages = document.getElementById('chatbot-messages');
        this.voiceStartBtn = document.getElementById('chatbot-voice-start');
        this.voiceStopBtn = document.getElementById('chatbot-voice-stop');
        this.voiceVolumeBtn = document.getElementById('chatbot-volume');
        this.voiceStatus = document.getElementById('voice-status');
        
        this.isOpen = false;
        this.isRecording = false;
        this.speakerEnabled = true;
        this.recognitionActive = false;
        this.mediaRecorder = null;
        this.audioChunks = [];
        
        // Initialize Speech Recognition
        this.recognition = null;
        this.initializeSpeechRecognition();
        
        // Initialize Speech Synthesis
        this.synth = window.speechSynthesis;
        
        this.bindEvents();
        this.scrollToBottom();
    }

    initializeSpeechRecognition() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            this.recognition = new SpeechRecognition();
            this.recognition.continuous = false;
            this.recognition.interimResults = true;
            this.recognition.lang = 'en-US';

            this.recognition.onstart = () => {
                this.isRecording = true;
                this.voiceStartBtn.classList.add('hidden');
                this.voiceStopBtn.classList.remove('hidden');
                this.voiceStatus.classList.remove('hidden');
                this.chatbotBtn.classList.add('voice-active');
            };

            this.recognition.onresult = (event) => {
                let interimTranscript = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    const transcript = event.results[i][0].transcript;
                    if (event.results[i].isFinal) {
                        this.chatbotInput.value = transcript;
                        this.sendMessage();
                    } else {
                        interimTranscript += transcript;
                    }
                }
                if (interimTranscript) {
                    this.chatbotInput.placeholder = `Listening: "${interimTranscript}"`;
                }
            };

            this.recognition.onend = () => {
                this.isRecording = false;
                this.voiceStartBtn.classList.remove('hidden');
                this.voiceStopBtn.classList.add('hidden');
                this.voiceStatus.classList.add('hidden');
                this.chatbotBtn.classList.remove('voice-active');
                this.chatbotInput.placeholder = 'Type your message...';
            };

            this.recognition.onerror = (event) => {
                console.error('Speech recognition error:', event.error);
                this.addMessageToDom('bot', `Voice recognition error: ${event.error}`);
                this.isRecording = false;
                this.voiceStartBtn.classList.remove('hidden');
                this.voiceStopBtn.classList.add('hidden');
                this.voiceStatus.classList.add('hidden');
            };
        }
    }

    bindEvents() {
        // Toggle chatbot container
        this.chatbotBtn.addEventListener('click', () => this.toggleChatbot());
        this.chatbotClose.addEventListener('click', () => this.toggleChatbot());

        // Send message events
        this.chatbotSend.addEventListener('click', () => this.sendMessage());
        this.chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });

        // Voice events
        this.voiceStartBtn.addEventListener('click', () => this.startVoiceRecognition());
        this.voiceStopBtn.addEventListener('click', () => this.stopVoiceRecognition());
        this.voiceVolumeBtn.addEventListener('click', () => this.toggleSpeaker());
    }

    toggleChatbot() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.chatbotContainer.classList.remove('hidden');
            this.chatbotInput.focus();
        } else {
            this.chatbotContainer.classList.add('hidden');
        }
    }

    sendMessage() {
        const message = this.chatbotInput.value.trim();
        if (!message) return;

        // Add user message to DOM
        this.addMessageToDom('user', message);
        this.chatbotInput.value = '';

        // Send to backend
        this.sendMessageToBackend(message);
    }

    async sendMessageToBackend(message) {
        try {
            // Show loading indicator
            this.addMessageToDom('bot', '🤔 Thinking...');

            const response = await fetch('/api/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'include',
                body: JSON.stringify({ message })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            // Remove loading message
            const messages = this.chatbotMessages.querySelectorAll('.message-bot');
            if (messages.length > 0) {
                const lastMessage = messages[messages.length - 1];
                if (lastMessage.textContent.includes('🤔')) {
                    lastMessage.remove();
                }
            }

            // Add bot response
            const botResponse = data.reply || data.message || 'I couldn\'t understand that. Please try again.';
            this.addMessageToDom('bot', botResponse);

            // Text-to-speech if enabled
            if (this.speakerEnabled && this.synth) {
                this.speak(botResponse);
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.addMessageToDom('bot', `Error: ${error.message}`);
        }
    }

    addMessageToDom(sender, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex gap-2 items-start ${sender === 'user' ? 'justify-end' : ''}`;

        if (sender === 'bot') {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-xs"></i>
                </div>
                <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs message-bot">
                    <p class="text-sm text-gray-700">${this.escapeHtml(message)}</p>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="bg-blue-600 text-white rounded-lg p-3 shadow-sm max-w-xs message-user">
                    <p class="text-sm">${this.escapeHtml(message)}</p>
                </div>
            `;
        }

        this.chatbotMessages.appendChild(messageDiv);
        this.scrollToBottom();
    }

    startVoiceRecognition() {
        if (this.recognition && !this.isRecording) {
            this.recognition.start();
        } else {
            this.addMessageToDom('bot', 'Speech recognition is not supported in your browser. Please use the text input instead.');
        }
    }

    stopVoiceRecognition() {
        if (this.recognition && this.isRecording) {
            this.recognition.stop();
        }
    }

    toggleSpeaker() {
        this.speakerEnabled = !this.speakerEnabled;
        this.voiceVolumeBtn.classList.toggle('bg-gray-600');
        this.voiceVolumeBtn.classList.toggle('bg-blue-600');
        
        if (!this.speakerEnabled) {
            this.synth.cancel();
        }
        
        const label = this.speakerEnabled ? 'Speaker on' : 'Speaker off';
        this.voiceVolumeBtn.title = label;
    }

    speak(text) {
        if (!this.synth || !this.speakerEnabled) return;

        // Cancel any ongoing speech
        this.synth.cancel();

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = 1;
        utterance.pitch = 1;
        utterance.volume = 1;
        utterance.lang = 'en-US';

        this.synth.speak(utterance);
    }

    scrollToBottom() {
        setTimeout(() => {
            this.chatbotMessages.scrollTop = this.chatbotMessages.scrollHeight;
        }, 0);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Quick action handler
function chatbotQuickAction(action) {
    const input = document.getElementById('chatbot-input');
    input.value = action;
    
    const chatbot = window.chatbotWidget;
    if (chatbot) {
        chatbot.sendMessage();
    }
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.chatbotWidget = new ChatbotWidget();
});
