<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'patient_id',
    ];

    public function diagnostics()
    {
        return $this->belongsToMany(Diagnostic::class, 'diagnostic_symptom', 'symptom_id', 'diagnostic_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
