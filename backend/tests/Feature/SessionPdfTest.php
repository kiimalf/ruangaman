<?php

namespace Tests\Feature;

use App\Models\ExpertSession;
use App\Models\Hypothesis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function export_pdf_returns_valid_pdf_stream()
    {
        $session = ExpertSession::create([
            'started_at' => now(),
        ]);

        $response = $this->getJson("/api/session/export-pdf?session_token={$session->id}");
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringContainsString('%PDF-', $response->streamedContent());
    }

    /** @test */
    public function export_pdf_requires_valid_session_token()
    {
        $response = $this->getJson("/api/session/export-pdf?session_token=invalid-uuid");
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['session_token']);
    }

    /** @test */
    public function export_pdf_works_with_concluded_session()
    {
        $hypothesis = Hypothesis::first();
        
        $session = ExpertSession::create([
            'started_at' => now(),
            'concluded_at' => now(),
            'conclusion_id' => $hypothesis->id,
        ]);

        $response = $this->getJson("/api/session/export-pdf?session_token={$session->id}");
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
