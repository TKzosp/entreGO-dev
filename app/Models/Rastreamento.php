<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rastreamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'rota_id',
        'latitude',
        'longitude',
        'data_hora',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
    ];
}
