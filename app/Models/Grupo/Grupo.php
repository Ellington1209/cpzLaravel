<?php

namespace App\Models\Grupo;

use App\Models\Membro\Membro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'descricao'];
    


    public function membros()
    {
        return $this->hasManyThrough(
            Membro::class,
            GrupoMembro::class,
            'grupo_id', // Chave estrangeira em grupo_membros
            'id',       // Chave primária em membros
            'id',       // Chave primária em grupos
            'membro_id' // Chave estrangeira em grupo_membros
        );
    }
}
