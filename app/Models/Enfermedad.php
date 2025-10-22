<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    use HasFactory;

    /**
     * Explicit table name to avoid incorrect pluralization by Eloquent.
     */
    protected $table = 'enfermedades';

    protected $fillable = ['name'];

    public function records()
    {
        return $this->belongsToMany(Record::class, 'enfermedad_record', 'enfermedad_id', 'record_id');
    }
}
