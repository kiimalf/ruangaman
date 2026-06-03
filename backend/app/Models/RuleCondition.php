<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuleCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_id',
        'question_id',
        'expected_answer',
    ];

    /**
     * Rule yang memiliki kondisi ini.
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    /**
     * Pertanyaan yang terkait dengan kondisi ini.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
