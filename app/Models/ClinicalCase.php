<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'steps',
        'language',
        'solution',
        'created_by',
        'title_es',
        'description_es',
        'steps_es',
        'solution_es',
        'options',
        'options_es',
        'options_ru',
        'correct_index',
        'title_ru',
        'description_ru',
        'steps_ru',
        'solution_ru',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
