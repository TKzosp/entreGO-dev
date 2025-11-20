<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rastreamento extends Model
{
    use HasFactory;

    // Define o nome da tabela explicitamente caso não siga o padrão plural
    protected $table = 'rastreamento'; 

    protected $fillable = [
        'rota_id',
        'latitude',
        'longitude',
        'carimbo_tempo', // ou o nome que deste na migration
    ];
}