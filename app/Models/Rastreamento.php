<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rastreamento extends Model
{
    use HasFactory;

    protected $table = 'rastreamento';

    protected $fillable = [
        'rota_id',
        'latitude',
        'longitude',
        'data_hora',
    ];
}
