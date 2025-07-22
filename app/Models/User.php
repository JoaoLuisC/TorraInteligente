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
        'email',
        'senha',
        'tipo',
        'imagem',
    ];

    // Esconde o campo senha na serialização
    protected $hidden = [
        'senha',
        'remember_token',
    ];

    // Ativa os timestamps padrão do Laravel
    public $timestamps = true;

    // Define quais campos de timestamp usar - usar os padrões do Laravel
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Define o campo de senha para autenticação
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Laravel Auth usa 'password', mas nossa tabela usa 'senha'
    public function getAuthPasswordName()
    {
        return 'senha';
    }

    // Accessor para password -> mapeado para senha
    public function getPasswordAttribute()
    {
        return $this->senha;
    }

    // Mutator para password -> salva em senha
    public function setPasswordAttribute($value)
    {
        $this->attributes['senha'] = $value;
    }

    // Mapeia os campos para compatibilidade com Laravel Auth
    public function getNameAttribute()
    {
        return $this->nome;
    }

    public function torradores()
    {
        return $this->hasMany(\App\Models\Torrador::class, 'usuario_id', 'id');
    }
}
