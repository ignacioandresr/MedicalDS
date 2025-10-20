<?php

 namespace App\Models;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 class Diagnostic extends Model
 {
 use HasFactory;
  protected $fillable = ['description', 'date', 'patient_id', 'user_id'];
  protected $casts = ['date' => 'date'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'diagnostic_symptom', 'diagnostic_id', 'symptom_id');
    }
    /**
     * Mutator: guarda el RUT sin puntos ni guion en la BD
     */
    public function setRutAttribute($value)
    {
        $this->attributes['rut'] = preg_replace('/[^0-9kK]/', '', $value);
    }

    /**
     * Accessor: devuelve el RUT formateado (12.345.678-9)
     */
    public function getRutAttribute($value)
    {
        $rut = preg_replace('/[^0-9kK]/', '', $value);

        if (strlen($rut) < 2) {
            return $rut;
        }

        $cuerpo = substr($rut, 0, -1);
        $dv = substr($rut, -1);

        // Inserta puntos cada 3 dígitos desde la derecha
        $cuerpo = preg_replace('/\B(?=(\d{3})+(?!\d))/', '.', $cuerpo);

        return $cuerpo . '-' . strtoupper($dv);
    }
 }
