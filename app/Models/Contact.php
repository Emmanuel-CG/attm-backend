<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'car_id',
        'buyerName',
        'buyerEmail',
        'buyerPhone',
        'message',
        'offeredPrice',
        'status',
    ];

        public function car()
    {
        return $this->belongsTo(User::class);
    }
}