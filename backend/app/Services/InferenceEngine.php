<?php

namespace App\Services;

use App\Models\ExpertSession;
use App\Models\Hypothesis;
use App\Models\Question;
use App\Models\Rule;
use App\Models\SessionAnswer;

class InferenceEngine
{
    /**
     * Start a new anonymous session.
     * Returns session token and the first question (always P1 — gateway question).
     */
    public function startSession(): array
    {
        $session = ExpertSession::create([
            'started_at' => now(),
        ]);

        $firstQuestion = $this->getNextQuestion($session);

        return [
            'session_token' => $session->id,
            'first_question' => $firstQuestion,
        ];
    }

    /**
     * Process a user's answer and determine the next step.
     * Returns either the next question or the final conclusion.
     */
    public function processAnswer(string $sessionToken, int $questionId, string $answer): array
    {
        $session = ExpertSession::findOrFail($sessionToken);

        // Prevent answering on concluded sessions
        if ($session->concluded_at !== null) {
            return [
                'next_question' => null,
                'conclusion' => $this->evaluateSessionConclusion($session, false),
            ];
        }

        // Prevent duplicate answers for the same question
        $existing = $session->answers()->where('question_id', $questionId)->first();
        if (!$existing) {
            SessionAnswer::create([
                'session_id' => $session->id,
                'question_id' => $questionId,
                'answer' => strtoupper($answer),
                'answered_at' => now(),
            ]);
        }

        // Run backward chaining to get next question
        $nextQuestion = $this->getNextQuestion($session->fresh());

        if ($nextQuestion !== null) {
            return [
                'next_question' => $nextQuestion,
                'conclusion' => null,
            ];
        }

        // No more questions — produce conclusion
        $conclusion = $this->conclude($session->fresh());

        return [
            'next_question' => null,
            'conclusion' => $conclusion,
        ];
    }

    /**
     * Backward Chaining: Determine the next question to ask.
     *
     * Algorithm:
     * 1. Check P1 (gateway) — if not answered, ask it
     * 2. If P1 = TIDAK → no more questions, conclude "Bukan TPKS"
     * 3. If P1 = SAYA_TIDAK_YAKIN → no more questions, conclude "Konsultasi Lanjut"
     * 4. If P1 = YA → iterate through hypotheses H1-H9 via rules
     * 5. For each rule's conditions, if unanswered → ask that question
     * 6. If all hypotheses fully evaluated → no more questions
     */
    public function getNextQuestion(ExpertSession $session): ?array
    {
        $answers = $session->answers()->pluck('answer', 'question_id')->toArray();

        // Step 1: Gateway question (P1 — always the first question in DB)
        $gatewayQuestion = Question::orderBy('id')->first();

        if (!$gatewayQuestion) {
            return null; // No questions in database
        }

        if (!isset($answers[$gatewayQuestion->id])) {
            return $this->formatQuestion($gatewayQuestion);
        }

        // Step 2-3: Check gateway answer
        $gatewayAnswer = strtoupper($answers[$gatewayQuestion->id]);
        if ($gatewayAnswer === 'TIDAK' || $gatewayAnswer === 'SAYA_TIDAK_YAKIN') {
            return null; // Conclude immediately
        }

        // Step 4-5: P1 = YA → backward chaining through all hypotheses
        // Get all rules grouped by hypothesis, excluding fallback (H10)
        $fallbackHypothesis = Hypothesis::where('pasal_uutpks', 'Pasal 4 Ayat (2)')->first();

        $rules = Rule::with(['conditions.question', 'hypothesis'])
            ->when($fallbackHypothesis, function ($query) use ($fallbackHypothesis) {
                $query->where('hypothesis_id', '!=', $fallbackHypothesis->id);
            })
            ->orderBy('id')
            ->get();

        // Group rules by hypothesis_id for proper evaluation
        $rulesByHypothesis = $rules->groupBy('hypothesis_id');

        foreach ($rulesByHypothesis as $hypothesisId => $hypothesisRules) {
            $hypothesisProven = false;

            foreach ($hypothesisRules as $rule) {
                $ruleStatus = $this->evaluateRule($rule, $answers);

                if ($ruleStatus === 'PROVEN') {
                    $hypothesisProven = true;
                    break; // This hypothesis is proven, no need to check more rules
                }

                if ($ruleStatus === 'NEED_ANSWER') {
                    // Find the first unanswered condition in this rule
                    foreach ($rule->conditions as $condition) {
                        if (!isset($answers[$condition->question_id])) {
                            return $this->formatQuestion($condition->question);
                        }
                    }
                }

                // REJECTED → try next rule for this hypothesis
            }

            // If proven, move to next hypothesis (support multi-conclusion)
            // If all rules rejected, move to next hypothesis
        }

        // Step 6: All hypotheses evaluated, no more questions needed
        return null;
    }

    /**
     * Evaluate a single rule against the collected answers.
     *
     * @return string 'PROVEN' | 'REJECTED' | 'NEED_ANSWER'
     */
    private function evaluateRule(Rule $rule, array $answers): string
    {
        foreach ($rule->conditions as $condition) {
            if (!isset($answers[$condition->question_id])) {
                return 'NEED_ANSWER';
            }

            $userAnswer = strtoupper($answers[$condition->question_id]);
            $expectedAnswer = strtoupper($condition->expected_answer);

            // 'SAYA_TIDAK_YAKIN' does not match 'YA' → treated as rejection
            if ($userAnswer !== $expectedAnswer) {
                return 'REJECTED';
            }
        }

        return 'PROVEN';
    }

    /**
     * Produce the final conclusion for a session.
     * Evaluates all rules and determines which hypotheses are proven.
     */
    public function conclude(ExpertSession $session): array
    {
        return $this->evaluateSessionConclusion($session, true);
    }

    private function evaluateSessionConclusion(ExpertSession $session, bool $saveToDb): array
    {
        $answers = $session->answers()->pluck('answer', 'question_id')->toArray();
        $gatewayQuestion = Question::orderBy('id')->first();

        // Case 1: P1 not answered or P1 = TIDAK → Bukan TPKS
        if (!$gatewayQuestion ||
            !isset($answers[$gatewayQuestion->id]) ||
            strtoupper($answers[$gatewayQuestion->id]) === 'TIDAK') {

            if ($saveToDb) {
                $session->update(['concluded_at' => now()]);
            }

            return [
                'type' => 'BUKAN_TPKS',
                'hypotheses' => [],
                'message' => 'Berdasarkan jawaban Anda, kejadian yang Anda alami belum memenuhi unsur tindak pidana kekerasan seksual sebagaimana diatur dalam UU TPKS.',
                'recommendation' => 'Jika Anda masih merasa tidak aman atau membutuhkan bantuan, silakan konsultasikan dengan Lembaga Bantuan Hukum (LBH) atau Satgas PPKS di institusi Anda.',
                'disclaimer' => 'Hasil ini bukan keputusan hukum final. Platform ini bertujuan memberikan pemahaman awal tentang UU TPKS.',
            ];
        }

        // Case 2: P1 = SAYA_TIDAK_YAKIN → Konsultasi Lanjut
        if (strtoupper($answers[$gatewayQuestion->id]) === 'SAYA_TIDAK_YAKIN') {
            if ($saveToDb) {
                $session->update(['concluded_at' => now()]);
            }

            return [
                'type' => 'KONSULTASI_LANJUT',
                'hypotheses' => [],
                'message' => 'Kami memahami bahwa situasi Anda mungkin kompleks dan sulit untuk dinilai sendiri. Kami menyarankan Anda untuk berkonsultasi dengan pihak yang kompeten.',
                'recommendation' => 'Hubungi Lembaga Bantuan Hukum (LBH) terdekat, Satgas PPKS, atau hotline kekerasan seksual untuk mendapatkan pendampingan lebih lanjut.',
                'disclaimer' => 'Hasil ini bukan keputusan hukum final. Platform ini bertujuan memberikan pemahaman awal tentang UU TPKS.',
            ];
        }

        // Case 3: P1 = YA → Evaluate all rules for proven hypotheses
        $rules = Rule::with(['conditions', 'hypothesis'])->get();
        $provenHypotheses = [];

        foreach ($rules as $rule) {
            if ($this->evaluateRule($rule, $answers) === 'PROVEN') {
                // Use hypothesis_id as key to deduplicate (multiple rules → same hypothesis)
                if (!isset($provenHypotheses[$rule->hypothesis_id])) {
                    $provenHypotheses[$rule->hypothesis_id] = $rule->hypothesis;
                }
            }
        }

        // Case 3a: At least one hypothesis proven
        if (!empty($provenHypotheses)) {
            $primary = reset($provenHypotheses);

            if ($saveToDb) {
                $session->update([
                    'concluded_at' => now(),
                    'conclusion_id' => $primary->id,
                ]);
            }

            return [
                'type' => 'TERPENUHI',
                'hypotheses' => array_values(array_map(fn(Hypothesis $h) => [
                    'id' => $h->id,
                    'label' => $h->label,
                    'pasal' => $h->pasal_uutpks,
                    'description' => $h->description,
                ], $provenHypotheses)),
                'message' => 'Berdasarkan jawaban Anda, kejadian yang Anda alami memenuhi unsur tindak pidana kekerasan seksual.',
                'recommendation' => 'Simpan hasil ini dan konsultasikan dengan Lembaga Bantuan Hukum (LBH) atau Satgas PPKS untuk langkah hukum selanjutnya. Anda juga dapat mengunduh draf dokumen kronologis sebagai bahan laporan.',
                'disclaimer' => 'Hasil ini bukan keputusan hukum final. Platform ini bertujuan memberikan pemahaman awal tentang UU TPKS.',
            ];
        }

        // Case 3b: P1=YA but no specific hypothesis proven → Fallback H10
        $fallback = Hypothesis::where('pasal_uutpks', 'Pasal 4 Ayat (2)')->first();

        if ($saveToDb) {
            $session->update([
                'concluded_at' => now(),
                'conclusion_id' => $fallback?->id,
            ]);
        }

        return [
            'type' => 'FALLBACK',
            'hypotheses' => $fallback ? [[
                'id' => $fallback->id,
                'label' => $fallback->label,
                'pasal' => $fallback->pasal_uutpks,
                'description' => $fallback->description,
            ]] : [],
            'message' => 'Berdasarkan jawaban Anda, terdapat indikasi adanya kekerasan seksual namun tidak terklasifikasi secara spesifik dalam kategori UU TPKS yang tersedia.',
            'recommendation' => 'Konsultasikan situasi Anda dengan Lembaga Bantuan Hukum (LBH) atau Satgas PPKS untuk evaluasi lebih lanjut oleh pihak yang berwenang.',
            'disclaimer' => 'Hasil ini bukan keputusan hukum final. Platform ini bertujuan memberikan pemahaman awal tentang UU TPKS.',
        ];
    }

    public function getConclusion(string $sessionToken): array
    {
        $session = ExpertSession::findOrFail($sessionToken);

        // Always re-evaluate so we can return ALL matching hypotheses,
        // not just the primary one saved in the database.
        return $this->evaluateSessionConclusion($session, false);
    }

    /**
     * Format a question for API response.
     */
    private function formatQuestion(Question $question): array
    {
        return [
            'id' => $question->id,
            'text' => $question->text,
            'help_text' => $question->help_text,
            'answer_type' => $question->answer_type,
        ];
    }

    // formatConclusion removed since it's no longer used
}
