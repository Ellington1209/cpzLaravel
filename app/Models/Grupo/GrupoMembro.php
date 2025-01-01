<?php

namespace App\Models\Grupo;

use App\Models\Membro\Membro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMembro extends Model
{
    use HasFactory;

    // Define a tabela associada
    protected $table = 'grupo_membros';

    // Desativa o incremento automático (chave composta é usada)
    public $incrementing = false;

    // Desativa os timestamps, se não forem necessários
    public $timestamps = false;

    // Define os relacionamentos, se necessário
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function membro()
    {
        return $this->belongsTo(Membro::class, 'membro_id');
    }
}
