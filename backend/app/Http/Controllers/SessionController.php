<?php

namespace App\Http\Controllers;

use App\Services\InferenceEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    public function __construct(
        private InferenceEngine $engine
    ) {}

    /**
     * POST /api/session/start
     * Start a new anonymous expert session.
     */
    public function start(): JsonResponse
    {
        $result = $this->engine->startSession();

        return response()->json([
            'success' => true,
            'data' => $result,
        ], 201);
    }

    /**
     * POST /api/session/answer
     * Submit an answer to a question and get the next question or conclusion.
     */
    public function answer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|uuid|exists:expert_sessions,id',
            'question_id' => 'required|integer|exists:questions,id',
            'answer' => 'required|string|in:YA,TIDAK,SAYA_TIDAK_YAKIN',
        ], [
            'session_token.exists' => 'Sesi tidak ditemukan.',
            'question_id.exists' => 'Pertanyaan tidak ditemukan.',
            'answer.in' => 'Jawaban harus berupa: YA, TIDAK, atau SAYA_TIDAK_YAKIN.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->engine->processAnswer(
            $request->input('session_token'),
            $request->input('question_id'),
            $request->input('answer'),
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * GET /api/session/conclude
     * Get the conclusion for a session.
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
                'errors' => $validator->errors(),
            ], 422);
        }

        $conclusion = $this->engine->getConclusion(
            $request->input('session_token'),
        );

        return response()->json([
            'success' => true,
            'data' => [
                'conclusion' => $conclusion,
            ],
        ]);
    }
}
