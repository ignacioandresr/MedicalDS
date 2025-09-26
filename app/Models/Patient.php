<?php

 namespace App\Models;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 class Patient extends Model
 {
 use HasFactory;
  protected $fillable = ['rut', 'name', 'birth_date','gender', 'adress'];
  protected $casts = ['birth_date' => 'date'];
 }


