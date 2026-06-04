<?php

namespace Tests\Feature;

use App\Models\ExpertSession;
use App\Models\Hypothesis;
use App\Models\Question;
use App\Models\Rule;
use App\Models\RuleCondition;
use App\Models\SessionAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InferenceEngineTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * Helper: get the gateway question (P1).
     */
    private function getGatewayQuestion(): Question
    {
        return Question::orderBy('id')->first();
    }

    /**
     * Helper: get question by its order index (1-based, P1=1, P2=2, etc.).
     */
    private function getQuestion(int $index): Question
    {
        return Question::orderBy('id')->get()->values()->get($index - 1);
    }

    /**
     * Helper: start a session and return the response data.
     */
    private function createSession(): array
    {
        $response = $this->postJson('/api/session/start');
        $response->assertStatus(201);
        return $response->json('data');
    }

    /**
     * Helper: answer a question and return the response data.
     */
    private function submitAnswer(string $token, int $questionId, string $answer): array
    {
        $response = $this->postJson('/api/session/answer', [
            'session_token' => $token,
            'question_id' => $questionId,
            'answer' => $answer,
        ]);
        $response->assertStatus(200);
        return $response->json('data');
    }

    // =========================================================================
    // SESSION START TESTS
    // =========================================================================

    public function test_start_session_returns_uuid_and_first_question(): void
    {
        $data = $this->createSession();

        $this->assertArrayHasKey('session_token', $data);
        $this->assertArrayHasKey('first_question', $data);
        $this->assertNotNull($data['first_question']);

        // First question should be P1 (gateway)
        $gateway = $this->getGatewayQuestion();
        $this->assertEquals($gateway->id, $data['first_question']['id']);

        // Session should exist in database
        $this->assertDatabaseHas('expert_sessions', [
            'id' => $data['session_token'],
        ]);
    }

    // =========================================================================
    // P1 = TIDAK → BUKAN TPKS
    // =========================================================================

    public function test_p1_tidak_concludes_bukan_tpks(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];
        $p1 = $data['first_question']['id'];

        $result = $this->submitAnswer($token, $p1, 'TIDAK');

        $this->assertNull($result['next_question']);
        $this->assertNotNull($result['conclusion']);
        $this->assertEquals('BUKAN_TPKS', $result['conclusion']['type']);
        $this->assertNotEmpty($result['conclusion']['disclaimer']);
    }

    // =========================================================================
    // P1 = SAYA_TIDAK_YAKIN → KONSULTASI LANJUT
    // =========================================================================

    public function test_p1_saya_tidak_yakin_concludes_konsultasi(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];
        $p1 = $data['first_question']['id'];

        $result = $this->submitAnswer($token, $p1, 'SAYA_TIDAK_YAKIN');

        $this->assertNull($result['next_question']);
        $this->assertNotNull($result['conclusion']);
        $this->assertEquals('KONSULTASI_LANJUT', $result['conclusion']['type']);
    }

    // =========================================================================
    // H1: PELECEHAN SEKSUAL NONFISIK (P1+P2+P3 = YA)
    // =========================================================================

    public function test_h1_pelecehan_nonfisik_proven(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        $p1 = $this->getQuestion(1);
        $p2 = $this->getQuestion(2);
        $p3 = $this->getQuestion(3);

        // Answer P1 = YA
        $result = $this->submitAnswer($token, $p1->id, 'YA');
        $this->assertNotNull($result['next_question']);
        $this->assertEquals($p2->id, $result['next_question']['id']);

        // Answer P2 = YA
        $result = $this->submitAnswer($token, $p2->id, 'YA');
        $this->assertNotNull($result['next_question']);
        $this->assertEquals($p3->id, $result['next_question']['id']);

        // Answer P3 = YA → H1 proven, but engine continues to check other hypotheses
        $result = $this->submitAnswer($token, $p3->id, 'YA');

        // Should either ask next question (P4 for H2) or conclude
        // Since P4 hasn't been answered, it should ask P4
        if ($result['next_question'] !== null) {
            // Answer remaining questions with TIDAK to reach conclusion
            $this->answerRemainingQuestions($token, $result);
        }

        // Verify session has conclusion
        $session = ExpertSession::find($token);
        $this->assertNotNull($session->concluded_at);

        // H1 should be in the conclusion
        $h1 = Hypothesis::where('pasal_uutpks', 'Pasal 5')->first();
        $this->assertEquals($h1->id, $session->conclusion_id);
    }

    // =========================================================================
    // H2: PELECEHAN SEKSUAL FISIK (P1+P4+P5 = YA)
    // =========================================================================

    public function test_h2_pelecehan_fisik_proven(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        $p1 = $this->getQuestion(1);

        // P1 = YA
        $result = $this->submitAnswer($token, $p1->id, 'YA');

        // P2 = TIDAK (reject H1)
        $p2 = $this->getQuestion(2);
        $result = $this->submitAnswer($token, $p2->id, 'TIDAK');

        // Next should be P4 (skip P3 since H1 already rejected by P2)
        $p4 = $this->getQuestion(4);
        $this->assertEquals($p4->id, $result['next_question']['id']);

        // P4 = YA
        $result = $this->submitAnswer($token, $p4->id, 'YA');

        // Next should be P5
        $p5 = $this->getQuestion(5);
        $this->assertEquals($p5->id, $result['next_question']['id']);

        // P5 = YA → H2 proven
        $result = $this->submitAnswer($token, $p5->id, 'YA');

        // Continue answering remaining questions with TIDAK
        if ($result['next_question'] !== null) {
            $this->answerRemainingQuestions($token, $result);
        }

        $session = ExpertSession::find($token);
        $this->assertNotNull($session->concluded_at);
    }

    // =========================================================================
    // H9: KSBE (P1+P14 = YA, via Rule 9A)
    // =========================================================================

    public function test_h9_ksbe_perekaman_proven(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        $p1 = $this->getQuestion(1);

        // P1 = YA
        $this->submitAnswer($token, $p1->id, 'YA');

        // Answer P2-P13 with TIDAK (reject H1-H8)
        for ($i = 2; $i <= 13; $i++) {
            $q = $this->getQuestion($i);
            $result = $this->submitAnswer($token, $q->id, 'TIDAK');

            // If we already got conclusion, break
            if ($result['conclusion'] !== null) break;
            if ($result['next_question'] === null) break;
        }

        // P14 = YA → H9 proven
        $p14 = $this->getQuestion(14);
        $result = $this->submitAnswer($token, $p14->id, 'YA');

        // Continue if needed
        if ($result['next_question'] !== null) {
            $this->answerRemainingQuestions($token, $result);
        }

        $session = ExpertSession::find($token);
        $this->assertNotNull($session->concluded_at);

        $h9 = Hypothesis::where('pasal_uutpks', 'Pasal 14')->first();
        $this->assertEquals($h9->id, $session->conclusion_id);
    }

    // =========================================================================
    // H10 FALLBACK: P1=YA but no specific hypothesis proven
    // =========================================================================

    public function test_h10_fallback_when_no_hypothesis_proven(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        $p1 = $this->getQuestion(1);

        // P1 = YA
        $this->submitAnswer($token, $p1->id, 'YA');

        // Answer ALL remaining questions with TIDAK
        $questions = Question::orderBy('id')->get()->slice(1);
        $result = null;
        foreach ($questions as $q) {
            $result = $this->submitAnswer($token, $q->id, 'TIDAK');
            if ($result['conclusion'] !== null) break;
            if ($result['next_question'] === null) break;
        }

        $this->assertNotNull($result['conclusion']);
        $this->assertEquals('FALLBACK', $result['conclusion']['type']);

        $h10 = Hypothesis::where('pasal_uutpks', 'Pasal 4 Ayat (2)')->first();
        $session = ExpertSession::find($token);
        $this->assertEquals($h10->id, $session->conclusion_id);
    }

    // =========================================================================
    // NO DEAD-END: Every combination must reach a conclusion
    // =========================================================================

    public function test_all_tidak_answers_reach_conclusion(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        $result = ['next_question' => $data['first_question']];

        // Answer every question with TIDAK
        while ($result['next_question'] !== null) {
            $qId = $result['next_question']['id'];
            $result = $this->submitAnswer($token, $qId, 'TIDAK');

            if ($result['conclusion'] !== null) break;
        }

        // Must have a conclusion — no dead-end
        $this->assertNotNull($result['conclusion']);
        $this->assertArrayHasKey('type', $result['conclusion']);
    }

    public function test_all_ya_answers_reach_conclusion(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        $result = ['next_question' => $data['first_question']];

        // Answer every question with YA
        while ($result['next_question'] !== null) {
            $qId = $result['next_question']['id'];
            $result = $this->submitAnswer($token, $qId, 'YA');

            if ($result['conclusion'] !== null) break;
        }

        $this->assertNotNull($result['conclusion']);
        $this->assertEquals('TERPENUHI', $result['conclusion']['type']);
        $this->assertNotEmpty($result['conclusion']['hypotheses']);
    }

    public function test_all_saya_tidak_yakin_reach_conclusion(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        $result = ['next_question' => $data['first_question']];

        // Answer every question with SAYA_TIDAK_YAKIN
        while ($result['next_question'] !== null) {
            $qId = $result['next_question']['id'];
            $result = $this->submitAnswer($token, $qId, 'SAYA_TIDAK_YAKIN');

            if ($result['conclusion'] !== null) break;
        }

        $this->assertNotNull($result['conclusion']);
        $this->assertArrayHasKey('type', $result['conclusion']);
    }

    // =========================================================================
    // CONCLUDE ENDPOINT
    // =========================================================================

    public function test_conclude_endpoint_returns_conclusion(): void
    {
        // Start and conclude a session
        $data = $this->createSession();
        $token = $data['session_token'];
        $p1 = $data['first_question']['id'];

        $this->submitAnswer($token, $p1, 'TIDAK');

        // Now call the conclude endpoint
        $response = $this->getJson('/api/session/conclude?session_token=' . $token);
        $response->assertStatus(200);
        $response->assertJsonPath('data.conclusion.type', 'BUKAN_TPKS');
    }

    // =========================================================================
    // VALIDATION TESTS
    // =========================================================================

    public function test_answer_rejects_invalid_session_token(): void
    {
        $response = $this->postJson('/api/session/answer', [
            'session_token' => '00000000-0000-0000-0000-000000000000',
            'question_id' => 1,
            'answer' => 'YA',
        ]);

        $response->assertStatus(422);
    }

    public function test_answer_rejects_invalid_answer_value(): void
    {
        $data = $this->createSession();

        $response = $this->postJson('/api/session/answer', [
            'session_token' => $data['session_token'],
            'question_id' => $data['first_question']['id'],
            'answer' => 'MUNGKIN',
        ]);

        $response->assertStatus(422);
    }

    // =========================================================================
    // MULTI-CONCLUSION TEST
    // =========================================================================

    public function test_multi_conclusion_supported(): void
    {
        $data = $this->createSession();
        $token = $data['session_token'];

        // Answer P1 = YA
        $p1 = $this->getQuestion(1);
        $this->submitAnswer($token, $p1->id, 'YA');

        // Answer P2=YA, P3=YA (H1 proven)
        $p2 = $this->getQuestion(2);
        $p3 = $this->getQuestion(3);
        $this->submitAnswer($token, $p2->id, 'YA');
        $this->submitAnswer($token, $p3->id, 'YA');

        // Answer P4=YA, P5=YA (H2 also proven)
        $p4 = $this->getQuestion(4);
        $p5 = $this->getQuestion(5);
        $this->submitAnswer($token, $p4->id, 'YA');
        $this->submitAnswer($token, $p5->id, 'YA');

        // Answer remaining with TIDAK to finish
        $questions = Question::orderBy('id')->get()->slice(5);
        $result = null;
        foreach ($questions as $q) {
            $result = $this->submitAnswer($token, $q->id, 'TIDAK');
            if ($result['conclusion'] !== null) break;
            if ($result['next_question'] === null) break;
        }

        // Conclusion should contain multiple hypotheses
        $this->assertNotNull($result['conclusion']);
        $this->assertEquals('TERPENUHI', $result['conclusion']['type']);
        $this->assertGreaterThanOrEqual(2, count($result['conclusion']['hypotheses']));
    }

    // =========================================================================
    // HELPER: Answer all remaining questions with TIDAK to reach conclusion
    // =========================================================================

    private function answerRemainingQuestions(string $token, array $lastResult): void
    {
        $result = $lastResult;
        $safetyCounter = 0;

        while ($result['next_question'] !== null && $safetyCounter < 20) {
            $qId = $result['next_question']['id'];
            $result = $this->submitAnswer($token, $qId, 'TIDAK');
            $safetyCounter++;

            if ($result['conclusion'] !== null) break;
        }
    }
}
