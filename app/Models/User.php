<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Agrega esta línea

class User extends Authenticatable
{
    use HasFactory, Notifiable; // 2. Agrega HasFactory aquí

    /**
     * Los atributos que se pueden llenar (Mass Assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'verified',
        'totalCars',
        'location',
        'bio',
        'curp',
        'rfc',
        'domicile',
        'ine',
        'api_token',
    ];

    /**
     * Los atributos que deben ocultarse en las respuestas JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Los atributos que deben ser casteados.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified' => 'boolean',
        'totalCars' => 'integer',
        'password' => 'hashed',
    ];
}
