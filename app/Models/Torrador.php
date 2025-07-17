<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Torrador extends Model
{
    protected $table = 'torradors';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'usuario_id',
        'codigo_conexao',
    ];

    protected $dates = ['criado_em'];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
