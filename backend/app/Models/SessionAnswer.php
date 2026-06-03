<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'question_id',
        'answer',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    /**
     * Sesi yang memiliki jawaban ini.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(ExpertSession::class, 'session_id');
    }

    /**
     * Pertanyaan yang dijawab.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
