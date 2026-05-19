<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
protected $fillable = [
    'car_id',
    'user_id',
    'reason',
    'details',
    'ip',
];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}