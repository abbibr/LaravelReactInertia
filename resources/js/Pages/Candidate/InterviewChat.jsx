import React, { useState } from "react";
import axios from "axios";

export default function InterviewPractice() {
  const [question, setQuestion] = useState(null);
  const [answer, setAnswer] = useState("");
  const [feedback, setFeedback] = useState("");
  const [loading, setLoading] = useState(false);
  const [step, setStep] = useState("start"); // 'start' | 'question' | 'completed'
  const [remaining, setRemaining] = useState(5);

  const startInterview = async () => {
    setLoading(true);
    try {
      const res = await axios.post("/ai-interview/start");
      setQuestion(res.data.question);
      setRemaining(5);
      setStep("question");
      setFeedback("");
    } catch (err) {
      console.error(err);
    }
    setLoading(false);
  };

  const submitAnswer = async () => {
    if (!answer.trim()) return;
    setLoading(true);
    try {
      const res = await axios.post("/ai-interview/answer", { answer });
      setFeedback(res.data.feedback);
      setAnswer("");
      setRemaining(res.data.remaining);

      // Scroll to feedback after short delay
      setTimeout(() => {
        const feedbackEl = document.getElementById("feedback-box");
        if (feedbackEl) feedbackEl.scrollIntoView({ behavior: "smooth" });
      }, 200);

      if (res.data.completed) {
        setStep("completed");
      } else {
        setQuestion(res.data.next_question);
      }
    } catch (err) {
      console.error(err);
    }
    setLoading(false);
  };

  return (
    <div className="max-w-2xl mx-auto p-6 bg-white shadow rounded-2xl mt-10">
      <h1 className="text-2xl font-bold mb-4 text-center">
        🧠 AI Interview Practice
      </h1>

      {step === "start" && (
        <div className="text-center">
          <p className="mb-4 text-gray-600">
            Start a mock interview session with AI to prepare for real
            interviews.
          </p>
          <button
            onClick={startInterview}
            disabled={loading}
            className="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
          >
            {loading ? "Starting..." : "Start Practice"}
          </button>
        </div>
      )}

      {step === "question" && question && (
        <>
          <div className="mb-4">
            <p className="text-sm text-gray-500 mb-2">
              Question {6 - remaining} of 5
            </p>

            <p className="text-lg font-semibold">🗣️ Interview Question:</p>
            <p className="text-gray-800 mt-1">{question}</p>
          </div>

          <textarea
            className="w-full p-3 border rounded mt-4"
            rows="4"
            placeholder="Type your answer here (you can write in Uzbek, Russian, or English)..."
            value={answer}
            onChange={(e) => setAnswer(e.target.value)}
          ></textarea>

          <button
            onClick={submitAnswer}
            disabled={loading || !answer.trim()}
            className={`mt-4 px-5 py-2 rounded text-white transition ${
              loading || !answer.trim()
                ? "bg-green-400 cursor-not-allowed"
                : "bg-green-600 hover:bg-green-700"
            }`}
          >
            {loading ? "Evaluating..." : "Submit Answer"}
          </button>

          {feedback && (
            <div
              id="feedback-box"
              className="mt-6 bg-gray-100 p-4 rounded shadow-inner"
            >
              <p className="font-semibold text-gray-700 mb-1">
                🧾 AI Feedback:
              </p>
              <p className="text-gray-800 whitespace-pre-line">{feedback}</p>
            </div>
          )}
        </>
      )}

      {step === "completed" && (
        <div className="text-center">
          <p className="text-xl font-semibold text-green-600">
            ✅ Interview Practice Completed!
          </p>
          <p className="text-gray-600 mt-2">
            You've finished all questions. Great job!
          </p>
          <button
            onClick={startInterview}
            className="mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
          >
            Restart Practice
          </button>
        </div>
      )}
    </div>
  );
}
