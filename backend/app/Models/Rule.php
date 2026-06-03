<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'hypothesis_id',
        'certainty_factor',
    ];

    protected $casts = [
        'certainty_factor' => 'decimal:2',
    ];

    /**
     * Hipotesis yang terkait dengan rule ini.
     */
    public function hypothesis(): BelongsTo
    {
        return $this->belongsTo(Hypothesis::class);
    }

    /**
     * Kondisi-kondisi yang harus dipenuhi rule ini.
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(RuleCondition::class);
    }
}
