<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Car extends Model
{
    protected $fillable = [
        'ai_price',
        'user_id',
        'brand',
        'model',
        'year',
        'price',
        'mileage',
        'transmission',
        'fuelType',
        'color',
        'location',
        'phone',
        'description',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reports()
{
    return $this->hasMany(Report::class);
}
}