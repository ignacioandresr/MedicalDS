<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralDiagnostic extends Model
{
    use HasFactory;

    protected $table = 'general_diagnostics';
    protected $fillable = ['description', 'date', 'user_id'];
    protected $casts = ['date' => 'date'];

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'general_diagnostic_symptom', 'general_diagnostic_id', 'symptom_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
