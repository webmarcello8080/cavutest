<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'name',
        'price',
        'from',
        'to',
        'is_weekend',
    ];

    protected $casts = [
        'from' => 'date',
        'to' => 'date',
    ];
}
