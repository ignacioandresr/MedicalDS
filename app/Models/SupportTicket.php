<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'status',
        'priority',
        'admin_response',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó el ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar tickets por estado
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar tickets por prioridad
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Obtener badge de color según el estado
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'abierto' => 'bg-primary',
            'en_progreso' => 'bg-warning',
            'resuelto' => 'bg-success',
            'cerrado' => 'bg-secondary',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    /**
     * Obtener badge de color según la prioridad
     */
    public function getPriorityBadgeAttribute()
    {
        $badges = [
            'baja' => 'bg-info',
            'media' => 'bg-primary',
            'alta' => 'bg-warning',
            'urgente' => 'bg-danger',
        ];

        return $badges[$this->priority] ?? 'bg-secondary';
    }
}
