<?php

namespace App\Http\Controllers\Grupo;

use App\Http\Controllers\Controller;
use App\Models\Grupo\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{

    public function index()
    {
        // Retorna todos os grupos sem membros associados
        $grupos = Grupo::all();
    
        return response()->json([
            'success' => true,
            'data' => $grupos
        ]);
    }
    
   public function cadastrarGrupo(Request $request)
   {
        $validacao = $request->validate([
            'nome' => 'required|string|min:3|max:80'
        ]);
        try{

            $grupo = Grupo::create( $validacao);

            return response()->json([
                'success' => true,
                'message' => 'Membro criado com sucesso.',
                'data' => $grupo,
            ], 201);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar membro: ' . $e->getMessage(),
            ], 500);
        }
   }
}
