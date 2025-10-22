<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alergia extends Model
{
    use HasFactory;

    /**
     * Explicit table name to avoid incorrect pluralization by Eloquent.
     */
    protected $table = 'alergias';

    protected $fillable = ['name'];

    public function records()
    {
        return $this->belongsToMany(Record::class, 'alergia_record', 'alergia_id', 'record_id');
    }
}
