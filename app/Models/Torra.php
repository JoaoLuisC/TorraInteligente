<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torra extends Model
{
    use HasFactory;

    protected $table = 'torras';

    protected $fillable = [
        'usuario_id',
        'nome',
        'variedade',
        'densidade',
        'fermentacao',
        'finalidade',
        'avaliada',
        'avaliador_id',
        'avaliada_em',
        'criado_em',
    ];

    public $timestamps = false;

    // Relacionamento com usuário (produtor)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relacionamento com avaliador (usuário)
    public function avaliador()
    {
        return $this->belongsTo(User::class, 'avaliador_id');
    }

    // Relacionamento com análises sensoriais
    public function analisesSensoriais()
    {
        return $this->hasMany(AnaliseSensorial::class, 'torra_id');
    }
}
