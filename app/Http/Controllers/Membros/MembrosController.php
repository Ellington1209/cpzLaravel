<?php

namespace App\Http\Controllers\Membros;

use App\Http\Controllers\Controller;
use App\Models\Membro\Membro;
use Illuminate\Http\Request;

class MembrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Filtra os membros onde o status é true
        $membros = Membro::where('status', true)->get();
    
        return response()->json([
            'success' => true,
            'data' => $membros
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nome_crente' => 'required|string|max:255',
            'telefone_celular' => 'required|string|max:15', // Adapte conforme o tamanho esperado
            'whatsapp' => 'required|in:S,N', // Aceita apenas 'S' ou 'N'
        ]);
    
        try {
            // Criação do membro no banco de dados
            $membro = Membro::create($validatedData);
    
            // Retorno de sucesso
            return response()->json([
                'success' => true,
                'message' => 'Membro criado com sucesso.',
                'data' => $membro,
            ], 201);
        } catch (\Exception $e) {
            // Tratamento de erros
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar membro: ' . $e->getMessage(),
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nome_crente' => 'required|string|max:255',
            'telefone_celular' => 'required|string|max:15',
            'whatsapp' => 'required|in:S,N',
        ]);
    
        $membro = Membro::findOrFail($id);
        $membro->update($validatedData);
    
        return response()->json([
            'success' => true,
            'message' => 'Membro atualizado com sucesso.',
            'data' => $membro,
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Busca o membro pelo ID
        $membro = Membro::findOrFail($id);
    
        // Atualiza o status para false
        $membro->update(['status' => false]);
    
        // Recarrega os dados atualizados do membro
        $membro->refresh();
    
        // Retorna uma resposta de sucesso
        return response()->json([
            'success' => true,
            'message' => 'Membro desativado com sucesso.',
            'data' => $membro,
        ]);
    }
    
}
