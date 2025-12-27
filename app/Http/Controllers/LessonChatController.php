<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LessonChatController extends Controller
{
    /**
     * Handle AI chat request for a lesson
     */
    public function chat(Request $request, $lessonId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $lesson = Lesson::with('course')->findOrFail($lessonId);
        $userMessage = $request->input('message');

        // Build lesson context
        $context = "Lesson Title: " . $lesson->title . "\n";
        if ($lesson->content) {
            $context .= "Lesson Content: " . strip_tags($lesson->content) . "\n";
        }
        if ($lesson->video_url) {
            $context .= "Video Reference: " . $lesson->video_url . "\n";
        }
        $context .= "Course: " . $lesson->course->title . "\n";

        $systemPrompt = "You are a helpful learning assistant for an online education platform called ByteLearn. 
Your role is to help students understand the lesson content better.

IMPORTANT RULES:
1. ONLY answer questions related to the lesson content provided below.
2. If a question is outside the scope of the lesson content, politely refuse and say: 'I can only help with questions related to this lesson's content. Please ask something about the topic we're learning.'
3. Be concise, friendly, and educational in your responses.
4. Use simple language that students can understand.
5. If you don't know something specific from the lesson, admit it and suggest reviewing the lesson material.

LESSON CONTEXT:
{$context}";

        $apiKey = config('ai.openrouter.api_key');
        $model = config('ai.openrouter.model');
        $siteUrl = config('ai.openrouter.site_url');
        $siteName = config('ai.openrouter.site_name');

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => $siteUrl,
                    'X-Title' => $siteName,
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage]
                    ],
                    'max_tokens' => 500,
                    'temperature' => 0.7,
                ]);

            if ($response->status() === 429) {
                return response()->json([
                    'error' => 'AI is busy right now. Please try again in a moment.'
                ], 429);
            }

            if (!$response->successful()) {
                \Log::error('LessonChat API Error', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json([
                    'error' => 'Unable to get a response. Please try again.'
                ], 500);
            }

            $data = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            \Log::error('LessonChat Exception: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
}
