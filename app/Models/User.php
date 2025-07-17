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
    protected $table = 'usuarios';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'tipo',
    ];

    // Esconde o campo senha na serialização
    protected $hidden = [
        'senha',
        'remember_token',
    ];

        // Ativa os timestamps padrão do Laravel, mas só criado_em
    public $timestamps = true;

    // Define qual campo de timestamp usar
    const CREATED_AT = 'criado_em';

    // Define o campo de senha para autenticação
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Mapeia os campos para compatibilidade com Laravel Auth
    public function getNameAttribute()
    {
        return $this->nome;
    }

    // Laravel Auth usa 'password', mas nossa tabela usa 'senha'
    public function getAuthPasswordName()
    {
        return 'senha';
    }

    public function torradores()
    {
        return $this->hasMany(\App\Models\Torrador::class, 'usuario_id', 'id');
    }
}
