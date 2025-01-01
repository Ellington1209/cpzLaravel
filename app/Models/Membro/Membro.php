<?php

namespace App\Models\Membro;

use App\Models\Grupo\Grupo;
use App\Models\Grupo\GrupoMembro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membro extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'nome_crente',
        'telefone_celular',
        'telefone_fixo',
        'whatsapp',
        'status'
    ];

    public function grupos()
    {
        return $this->hasManyThrough(
            Grupo::class,
            GrupoMembro::class,
            'membro_id', // Chave estrangeira em grupo_membros
            'id',        // Chave primária em grupos
            'id',        // Chave primária em membros
            'grupo_id'   // Chave estrangeira em grupo_membros
        );
    }
}
