<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hypothesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'pasal_uutpks',
        'description',
        'bap_template',
    ];

    /**
     * Rules yang terkait dengan hipotesis ini.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }

    /**
     * Sesi yang berakhir dengan hipotesis ini sebagai konklusi.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(ExpertSession::class, 'conclusion_id');
    }
}
