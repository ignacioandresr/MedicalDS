<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'date',
        'time',
        'notes',
        'status',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Human readable status label (Spanish).
     */
    public function getStatusLabelAttribute()
    {
        $map = [
            'scheduled' => 'agendado',
            'completed' => 'completada',
            'canceled' => 'cancelada',
            'cancelled' => 'cancelada',
            'pending' => 'pendiente',
        ];

        $status = $this->status ?? 'scheduled';

        return $map[$status] ?? $status;
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
