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

    // Permite acessar 'name' como alias para 'nome'
    public function getNameAttribute()
    {
        return $this->nome;
    }

    public function getCreatedAtAttribute()
    {
        $value = $this->attributes['criado_em'] ?? null;
        return $value ? Carbon::parse($value) : null;
    }

    public function torradores()
    {
        return $this->hasMany(\App\Models\Torrador::class, 'usuario_id', 'id');
    }
}
