<?php

namespace App\Http\Controllers\Membros;

use App\Http\Controllers\Controller;
use App\Models\Membro\Membro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Filtra os membros onde o status é true e carrega os grupos associados
        $membros = Membro::where('status', true)
            ->with('grupos') // Carrega os grupos associados
            ->get();

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
            'grupos' => 'array', // Certifica-se de que 'grupos' é um array
            'grupos.*' => 'integer|exists:grupos,id', // Cada item do array deve ser um ID válido em 'grupos'
        ]);

        // Transação para garantir atomicidade
        try {
            DB::beginTransaction();

            // Criação do membro no banco de dados
            $membro = Membro::create($validatedData);

            // Associa os grupos ao membro
            if (!empty($validatedData['grupos'])) {
                $gruposData = array_map(function ($grupoId) use ($membro) {
                    return [
                        'membro_id' => $membro->id,
                        'grupo_id' => $grupoId,
                    ];
                }, $validatedData['grupos']);

                // Inserção em grupo_membros
                DB::table('grupo_membros')->insert($gruposData);
            }

            DB::commit();

            // Retorno de sucesso
            return response()->json([
                'success' => true,
                'message' => 'Membro criado com sucesso.',
                'data' => $membro->load('grupos'), // Carrega os grupos associados
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

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
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nome_crente' => 'required|string|max:255',
            'telefone_celular' => 'required|string|max:15',
            'whatsapp' => 'required|in:S,N',
            'grupos' => 'array', // Certifica-se de que 'grupos' é um array
            'grupos.*' => 'integer|exists:grupos,id', // Cada item do array deve ser um ID válido em 'grupos'
        ]);

        try {
            // Inicia uma transação
            DB::beginTransaction();

            // Busca o membro e atualiza os dados básicos
            $membro = Membro::findOrFail($id);
            $membro->update($validatedData);

            // Atualiza os grupos associados
            if (!empty($validatedData['grupos'])) {
                $gruposData = array_map(function ($grupoId) use ($membro) {
                    return [
                        'membro_id' => $membro->id,
                        'grupo_id' => $grupoId,
                    ];
                }, $validatedData['grupos']);

                // Atualiza em grupo_membros
                DB::table('grupo_membros')
                    ->where('membro_id', $membro->id)
                    ->delete(); // Remove associações antigas

                DB::table('grupo_membros')->insert($gruposData); // Insere os novos grupos
            }

            // Confirma a transação
            DB::commit();

            // Retorna a resposta
            return response()->json([
                'success' => true,
                'message' => 'Membro atualizado com sucesso.',
                'data' => $membro->load('grupos'), // Retorna o membro com os grupos associados
            ]);
        } catch (\Exception $e) {
            // Reverte a transação em caso de erro
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar membro: ' . $e->getMessage(),
            ], 500);
        }
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
