<?php

namespace App\Http\Controllers;

use App\Models\Hypothesis;
use App\Services\InferenceEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ExpertSession;

class SessionController extends Controller
{
    public function __construct(
        private InferenceEngine $engine
    ) {}

    /**
     * POST /api/session/start
     */
    public function start(): JsonResponse
    {
        $result = $this->engine->startSession();

        return response()->json([
            'success' => true,
            'data'    => $result,
        ], 201);
    }

    /**
     * POST /api/session/answer
     */
    public function answer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|uuid|exists:expert_sessions,id',
            'question_id'   => 'required|integer|exists:questions,id',
            'answer'        => 'required|string|in:YA,TIDAK,SAYA_TIDAK_YAKIN',
        ], [
            'session_token.exists' => 'Sesi tidak ditemukan.',
            'question_id.exists'   => 'Pertanyaan tidak ditemukan.',
            'answer.in'            => 'Jawaban harus berupa: YA, TIDAK, atau SAYA_TIDAK_YAKIN.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $result = $this->engine->processAnswer(
            $request->input('session_token'),
            $request->input('question_id'),
            $request->input('answer'),
        );

        return response()->json([
            'success' => true,
            'data'    => $result,
        ]);
    }

    /**
     * GET /api/session/conclude
     */
    public function conclude(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|uuid|exists:expert_sessions,id',
        ], [
            'session_token.exists' => 'Sesi tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $conclusion = $this->engine->getConclusion(
            $request->input('session_token'),
        );

        return response()->json([
            'success' => true,
            'data'    => ['conclusion' => $conclusion],
        ]);
    }

    /**
     * GET /api/session/export-pdf
     *
     * Mengambil data sesi, membangun variabel klasifikasi & rangkuman
     * dari bap_template tiap hipotesis yang terbukti, lalu render PDF.
     */
    public function exportPdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|uuid|exists:expert_sessions,id',
        ], [
            'session_token.exists' => 'Sesi tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $sessionToken = $request->input('session_token');

        // Load sesi beserta jawaban dan pertanyaan terkait
        $session = ExpertSession::with(['answers.question'])->findOrFail($sessionToken);

        // Format jawaban untuk halaman 3 (Q&A log), urut berdasarkan waktu dijawab
        $formattedAnswers = $session->answers
            ->sortBy('answered_at')
            ->map(fn($answer) => [
                'question_text' => $answer->question->text,
                'answer'        => $answer->answer, // 'YA' | 'TIDAK' | 'SAYA_TIDAK_YAKIN'
            ])
            ->values()
            ->toArray();

        // Ambil konklusi dari InferenceEngine (berisi array hypotheses proven)
        $conclusion = $this->engine->getConclusion($sessionToken);

        // ----------------------------------------------------------------
        // Bangun 'klasifikasi' dan 'rangkuman' untuk halaman 2
        //
        // 'klasifikasi' : gabungan pasal dari semua hipotesis yang terbukti
        //                 contoh: "Pasal 5 & Pasal 14 UU TPKS No. 12 Tahun 2022"
        //
        // 'rangkuman'   : gabungan bap_template dari tiap hipotesis yang terbukti
        //                 (bap_template ditulis pakar hukum per hipotesis di DB)
        // ----------------------------------------------------------------
        $klasifikasi = '';
        $rangkuman   = '';

        if (!empty($conclusion['hypotheses'])) {
            // Ambil ID hipotesis yang proven — deduplikasi untuk mencegah pasal ganda
            // (InferenceEngine bisa return hipotesis yang sama jika ada >1 rule proven)
            $hypothesisIds = array_values(array_unique(
                array_column($conclusion['hypotheses'], 'id')
            ));

            // Load hipotesis dari DB untuk mendapatkan bap_template
            // (InferenceEngine tidak menyertakan field ini di response-nya)
            $hypothesesWithTemplate = Hypothesis::whereIn('id', $hypothesisIds)
                ->orderByRaw('FIELD(id, ' . implode(',', $hypothesisIds) . ')')
                ->get();

            // Susun string klasifikasi: "Pasal 5 & Pasal 14 UU TPKS No. 12 Tahun 2022"
            // unique() di collection level untuk jaga-jaga jika pasal_uutpks sama
            $pasalList = $hypothesesWithTemplate->pluck('pasal_uutpks')->unique()->values()->toArray();
            $klasifikasi = implode(' & ', $pasalList) . ' UU TPKS No. 12 Tahun 2022';

            // Gabungkan bap_template tiap hipotesis dengan pemisah paragraf
            $rangkumanParts = $hypothesesWithTemplate
                ->pluck('bap_template')
                ->filter() // buang yang null / kosong
                ->toArray();

            $rangkuman = implode("\n\n", $rangkumanParts);

        } else {
            // Fallback: sesi tanpa hipotesis spesifik (BUKAN_TPKS / KONSULTASI_LANJUT)
            $klasifikasi = 'Belum terklasifikasi secara spesifik';
            $rangkuman   = $conclusion['message'] ?? '';
        }

        // Gabungkan semua data yang dibutuhkan view
        $data = [
            // Data sesi: id (UUID) dan started_at untuk header tiap halaman
            'session'  => [
                'id'         => $session->id,
                'started_at' => $session->started_at,
            ],

            // Konklusi lengkap + field tambahan untuk halaman 2
            'conclusion' => array_merge($conclusion, [
                'klasifikasi' => $klasifikasi,
                'rangkuman'   => $rangkuman,
            ]),

            // Jawaban sesi untuk halaman 3
            'answers' => $formattedAnswers,
        ];

        $pdf = Pdf::loadView('pdf.bap_kronologis', $data)
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->stream();
            },
            'Draf_Kronologis_RuangAman.pdf',
            ['Content-Type' => 'application/pdf'],
        );
    }
}