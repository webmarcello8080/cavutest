<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer',
        'price',
        'from',
        'to',
    ];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];
}
