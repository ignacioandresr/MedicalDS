<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cirugia extends Model
{
    use HasFactory;

    /**
     * Explicit table name to avoid incorrect pluralization by Eloquent.
     */
    protected $table = 'cirugias';

    protected $fillable = ['name'];

    public function records()
    {
        return $this->belongsToMany(Record::class, 'cirugia_record', 'cirugia_id', 'record_id');
    }
}
