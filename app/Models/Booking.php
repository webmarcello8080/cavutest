<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
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
