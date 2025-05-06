<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AIInterviewController extends Controller
{
    /* protected $questions = [
        "Tell me about a time you faced a challenge at work and how you handled it?",
        "What are your greatest strengths and how have you used them professionally?",
        "Describe a situation where you had to work with a difficult team member?",
        "Why do you want this job?",
        "Where do you see yourself in 5 years?"
    ];

    public function start(Request $request)
    {
        Session::put('question_index', 0);

        $firstQuestion = $this->questions[0];

        return response()->json([
            'question' => $firstQuestion
        ]);
    }

    public function answer(Request $request)
    {
        $answer = $request->input('answer');

        // Get current question index from session
        $index = Session::get('question_index', 0);

        // Language-aware prompt
        $languageNote = "Answer and feedback must be in the same language the candidate used.";

        $prompt = "You are a professional HR assistant. A candidate answered: \"$answer\". $languageNote Evaluate their answer and give constructive feedback based on clarity, confidence, relevance, and communication style.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful, multilingual HR assistant preparing candidates for interviews. Always reply in the same language the candidate used.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $data = $response->json();

        // Prepare feedback
        $feedback = $data['choices'][0]['message']['content'] ?? null;

        // Move to next question
        $index++;
        Session::put('question_index', $index);

        $nextQuestion = $this->questions[$index] ?? null;

        return response()->json([
            'feedback' => $feedback,
            'next_question' => $nextQuestion,
            'completed' => $nextQuestion === null,
        ]);
    } */

    private $questionPool = [
        "Tell me about a time you faced a challenge at work and how you handled it.",
        "Describe a situation where you had to work under pressure.",
        "How do you handle feedback and criticism?",
        "What are your biggest strengths and weaknesses?",
        "Why do you want to work in this industry?",
        "Give an example of a goal you set and how you achieved it.",
        "Describe a time when you had a conflict with a team member.",
        "How do you prioritize tasks when you have multiple deadlines?",
        "What motivates you to do your best work?",
        "How do you handle failure or setbacks?",
        "Describe a time you demonstrated leadership.",
        "Tell me about a time you had to learn something quickly.",
        "How do you adapt to changes in the workplace?",
        "Give an example of a time you solved a problem creatively.",
        "Why should we hire you over other candidates?",
        "How do you handle working with difficult people?",
        "What is your approach to teamwork?",
        "What are your career goals for the next 5 years?"
    ];

    public function start(Request $request)
    {
        $session = $request->session();

        // Get random 5 questions from the pool
        $randomQuestions = collect($this->questionPool)
            ->shuffle()
            ->take(5)
            ->values()
            ->toArray();

        $session->put('interview_questions', $randomQuestions);
        $session->put('current_question_index', 0);

        return response()->json([
            'question' => $randomQuestions[0],
            'remaining' => 5
        ]);
    }

    public function answer(Request $request)
    {
        $answer = $request->input('answer');
        $session = $request->session();

        $questions = $session->get('interview_questions', []);
        $index = $session->get('current_question_index', 0);

        if ($index >= count($questions)) {
            return response()->json(['error' => 'No more questions'], 400);
        }

        $prompt = "You are a professional HR interviewer. A candidate answered: \"$answer\". Evaluate their answer and give constructive feedback based on clarity, confidence, relevance, and communication style. Respond in the same language the answer was written in.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful HR assistant preparing candidates for job interviews.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $data = $response->json();

        $feedback = $data['choices'][0]['message']['content'] ?? null;

        // Increment index for next question
        $index++;
        $session->put('current_question_index', $index);

        $nextQuestion = $questions[$index] ?? null;
        $remaining = max(0, count($questions) - $index);

        return response()->json([
            'feedback' => $feedback,
            'completed' => is_null($nextQuestion),
            'next_question' => $nextQuestion,
            'remaining' => $remaining,
        ]);
    }
}
