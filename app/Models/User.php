<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define a tabela correta
    protected $table = 'users';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Esconde o campo senha na serialização
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Ativa os timestamps padrão do Laravel
    public $timestamps = true;

    // Define o campo de senha para autenticação
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Permite acessar 'name' como alias para 'nome'
    public function getNameAttribute()
    {
        return $this->nome;
    }

    public function torradores()
    {
        return $this->hasMany(\App\Models\Torrador::class, 'usuario_id', 'id');
    }
}
