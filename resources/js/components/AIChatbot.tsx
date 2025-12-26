import React, { useState, useRef, useEffect } from 'react';
import { Send, Bot, User, Sparkles, Loader, X, Minimize2, Maximize2 } from 'lucide-react';
import { Button } from './Button';
import { Avatar } from './ui/Avatar';

interface Message {
    id: number;
    text: string;
    sender: 'user' | 'bot';
    timestamp: Date;
    isLoading?: boolean;
}

interface AIChatbotProps {
    lessonId?: number;
    courseId?: number;
    onClose?: () => void;
    isOpen?: boolean;
    minimized?: boolean;
    onToggleMinimize?: () => void;
}

export function AIChatbot({ 
    lessonId, 
    courseId, 
    onClose, 
    isOpen = true,
    minimized = false,
    onToggleMinimize 
}: AIChatbotProps) {
    const [messages, setMessages] = useState<Message[]>([
        {
            id: 1,
            text: "Hello! I'm your AI learning assistant. I can help you understand the course content, answer questions, and provide explanations. How can I assist you today?",
            sender: 'bot',
            timestamp: new Date()
        }
    ]);
    const [inputValue, setInputValue] = useState('');
    const [isTyping, setIsTyping] = useState(false);
    const messagesEndRef = useRef<HTMLDivElement>(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    const handleSendMessage = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!inputValue.trim()) return;

        const userMessage: Message = {
            id: Date.now(),
            text: inputValue,
            sender: 'user',
            timestamp: new Date()
        };

        setMessages(prev => [...prev, userMessage]);
        setInputValue('');
        setIsTyping(true);

        try {
            // Call AI API endpoint
            const response = await fetch(`/api/chatbot/ask`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': (window as any).csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    question: inputValue,
                    lesson_id: lessonId,
                    course_id: courseId
                })
            });

            const data = await response.json();

            const botMessage: Message = {
                id: Date.now() + 1,
                text: data.answer || "I'm sorry, I couldn't process that request. Please try again.",
                sender: 'bot',
                timestamp: new Date()
            };

            setMessages(prev => [...prev, botMessage]);
        } catch (error) {
            console.error('Chatbot error:', error);
            const errorMessage: Message = {
                id: Date.now() + 1,
                text: "I apologize, but I'm having trouble connecting right now. Please try again in a moment.",
                sender: 'bot',
                timestamp: new Date()
            };
            setMessages(prev => [...prev, errorMessage]);
        } finally {
            setIsTyping(false);
        }
    };

    const quickQuestions = [
        "Explain this concept in simple terms",
        "Give me a practical example",
        "What are the key takeaways?",
        "Test my understanding"
    ];

    if (!isOpen) return null;

    if (minimized) {
        return (
            <div className="fixed bottom-4 right-4 z-50">
                <button
                    onClick={onToggleMinimize}
                    className="bg-blue-600 text-white rounded-full p-4 shadow-lg hover:bg-blue-700 transition-all hover:scale-110"
                >
                    <Bot className="w-6 h-6" />
                </button>
            </div>
        );
    }

    return (
        <div className="fixed bottom-4 right-4 z-50 w-96 max-w-[calc(100vw-2rem)]">
            <div className="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col" style={{ height: '600px', maxHeight: '80vh' }}>
                {/* Header */}
                <div className="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-4 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <div className="bg-white/20 p-2 rounded-lg">
                            <Bot className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="font-semibold">AI Learning Assistant</h3>
                            <p className="text-xs text-blue-100">Powered by RAG</p>
                        </div>
                    </div>
                    <div className="flex items-center gap-2">
                        {onToggleMinimize && (
                            <button 
                                onClick={onToggleMinimize}
                                className="p-1 hover:bg-white/20 rounded transition-colors"
                            >
                                <Minimize2 className="w-5 h-5" />
                            </button>
                        )}
                        {onClose && (
                            <button 
                                onClick={onClose}
                                className="p-1 hover:bg-white/20 rounded transition-colors"
                            >
                                <X className="w-5 h-5" />
                            </button>
                        )}
                    </div>
                </div>

                {/* Messages */}
                <div className="flex-1 overflow-y-auto p-4 space-y-4">
                    {messages.map((message) => (
                        <div
                            key={message.id}
                            className={`flex gap-3 ${message.sender === 'user' ? 'flex-row-reverse' : 'flex-row'}`}
                        >
                            {/* Avatar */}
                            <div className={`flex-shrink-0 ${message.sender === 'user' ? 'order-1' : ''}`}>
                                {message.sender === 'bot' ? (
                                    <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <Bot className="w-5 h-5 text-white" />
                                    </div>
                                ) : (
                                    <div className="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <User className="w-5 h-5 text-gray-600" />
                                    </div>
                                )}
                            </div>

                            {/* Message Bubble */}
                            <div className={`flex-1 ${message.sender === 'user' ? 'text-right' : 'text-left'}`}>
                                <div
                                    className={`inline-block px-4 py-2.5 rounded-2xl max-w-[85%] ${
                                        message.sender === 'user'
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-gray-100 text-gray-900'
                                    }`}
                                >
                                    <p className="text-sm whitespace-pre-wrap">{message.text}</p>
                                </div>
                                <div className="text-xs text-gray-500 mt-1 px-2">
                                    {message.timestamp.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </div>
                            </div>
                        </div>
                    ))}

                    {/* Typing Indicator */}
                    {isTyping && (
                        <div className="flex gap-3">
                            <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                <Bot className="w-5 h-5 text-white" />
                            </div>
                            <div className="inline-block px-4 py-2.5 rounded-2xl bg-gray-100">
                                <div className="flex gap-1">
                                    <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: '0ms' }}></div>
                                    <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: '150ms' }}></div>
                                    <div className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: '300ms' }}></div>
                                </div>
                            </div>
                        </div>
                    )}

                    <div ref={messagesEndRef} />
                </div>

                {/* Quick Questions */}
                {messages.length === 1 && (
                    <div className="px-4 pb-2">
                        <p className="text-xs text-gray-500 mb-2">Quick questions:</p>
                        <div className="flex flex-wrap gap-2">
                            {quickQuestions.map((question, index) => (
                                <button
                                    key={index}
                                    onClick={() => setInputValue(question)}
                                    className="text-xs px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-full text-gray-700 transition-colors"
                                >
                                    {question}
                                </button>
                            ))}
                        </div>
                    </div>
                )}

                {/* Input Area */}
                <form onSubmit={handleSendMessage} className="p-4 border-t border-gray-200">
                    <div className="flex gap-2">
                        <input
                            type="text"
                            value={inputValue}
                            onChange={(e) => setInputValue(e.target.value)}
                            placeholder="Ask me anything about this lesson..."
                            className="flex-1 px-4 py-2.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            disabled={isTyping}
                        />
                        <Button
                            type="submit"
                            variant="primary"
                            size="md"
                            className="rounded-full px-4"
                            disabled={isTyping || !inputValue.trim()}
                        >
                            <Send className="w-4 h-4" />
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    );
}
