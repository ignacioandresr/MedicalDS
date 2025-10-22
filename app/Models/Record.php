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
        'antecedentes_salud',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class);
    }

    public function enfermedades()
    {
        return $this->belongsToMany(Enfermedad::class, 'enfermedad_record', 'record_id', 'enfermedad_id');
    }

    public function alergias()
    {
        return $this->belongsToMany(Alergia::class, 'alergia_record', 'record_id', 'alergia_id');
    }

    public function cirugias()
    {
        return $this->belongsToMany(Cirugia::class, 'cirugia_record', 'record_id', 'cirugia_id');
    }
    public function getAntecedentesSaludAttribute()
    {
        return $this->attributes['tratamientos'] ?? null;
    }
    public function setAntecedentesSaludAttribute($value)
    {
        $this->attributes['tratamientos'] = $value;
    }
}
