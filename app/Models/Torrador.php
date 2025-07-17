<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Torrador extends Model
{
    protected $table = 'torradors';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // Define qual campo de timestamp usar
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null; // Desabilita updated_at

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
