<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $primaryKey = 'id_historial';
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'diagnostic_id',
        'tratamientos',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class);
    }
}
