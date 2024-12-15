<?php

namespace App\Models\Membro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membro extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'name_fantasia',
        'telefone_celular',
        'telefone_fixo',
        'whatsapp',
    ];
}
