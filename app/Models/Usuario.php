<?php

// namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// // use Illuminate\Database\Eloquent\Factories\HasFactory;
// // use Illuminate\Foundation\Auth\User as Authenticatable;
// // use Illuminate\Notifications\Notifiable;
// // use Illuminate\Support\Str;


// class Usuario extends Authenticatable
// {
//     protected $table = 'usuarios';
//     protected $hidden = ['senha'];

//     public function getAuthPassword()
//     {
//         return $this->senha;
//     }
//     /** @use HasFactory<\Database\Factories\UserFactory> */
//     use HasFactory, Notifiable;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var list<string>
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//         'cpf',
//         'tel_number',
//         'role',
//         'status',
//         'birthdate',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var list<string>
//      */
//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }

//     /**
//      * Get the user's initials
//      */
//     public function initials(): string
//     {
//         return Str::of($this->name)
//             ->explode(' ')
//             ->map(fn (string $name) => Str::of($name)->substr(0, 1))
//             ->implode('');
//    }
// }

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome', 'email', 'senha', 'tipo', 'cpf_cnpj', 'telefone', 'data_cadastro', 'ativo'
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ativo' => 'boolean',
    ];
}

