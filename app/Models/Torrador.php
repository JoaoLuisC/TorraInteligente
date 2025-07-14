<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Torrador extends Model
{
    protected $table = 'torradores';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nome',
        'usuario_id',
        'codigo_conexao',
    ];

    protected $dates = ['criado_em'];
    public $timestamps = false;
}
