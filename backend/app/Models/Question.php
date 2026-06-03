<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'help_text',
        'answer_type',
    ];

    /**
     * Kondisi rule yang menggunakan pertanyaan ini.
     */
    public function ruleConditions(): HasMany
    {
        return $this->hasMany(RuleCondition::class);
    }

    /**
     * Jawaban sesi yang menjawab pertanyaan ini.
     */
    public function sessionAnswers(): HasMany
    {
        return $this->hasMany(SessionAnswer::class);
    }
}
