<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpertSession extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'expert_sessions';

    protected $fillable = [
        'started_at',
        'concluded_at',
        'conclusion_id',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'concluded_at' => 'datetime',
    ];

    /**
     * Hipotesis konklusi dari sesi ini (nullable).
     */
    public function conclusion(): BelongsTo
    {
        return $this->belongsTo(Hypothesis::class, 'conclusion_id');
    }

    /**
     * Jawaban-jawaban dalam sesi ini.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SessionAnswer::class, 'session_id');
    }
}
