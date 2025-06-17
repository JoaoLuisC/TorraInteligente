<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define a tabela correta
    protected $table = 'usuarios';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'nome',
        'sobrenome',
        'tipo',
        'email',
        'senha',
    ];

    // Esconde o campo senha na serialização
    protected $hidden = [
        'senha',
    ];

    // Desativa os timestamps padrão do Laravel
    public $timestamps = false;

    // Define o campo de senha para autenticação
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
