<?php

namespace App\Http\Controllers\Excel;

use App\Http\Controllers\Controller;
use App\Models\Membro\Membro;
use App\Models\User;
use Illuminate\Http\Request;

class LeitorExcelController extends Controller
{
   
    public function store(Request $request)
    {
        try {
            // Obtém o arquivo enviado
            $file = $request->file('excel_file');
            $filePath = $file->getPathname();
            $mimeType = $file->getMimeType();

            // Instância do ExcelController para processar o arquivo
            $excelController = new ExcelController();
            $data = $excelController->import($filePath, $mimeType);

            // Remove o cabeçalho
            $header = array_shift($data);

            // Mapeia os índices dos campos necessários
            $nameIndex = array_search('nome', $header);
            $cellPhoneIndex = array_search('telefone_celular', $header);
            $landlineIndex = array_search('telefone_fixo', $header);
            $whatsappIndex = array_search('whatsapp', $header);

            // Processa os dados e salva no banco
            foreach ($data as $row) {
                Membro::create([
                    'name' => $row[$nameIndex] ?? null,
                    'telefone_celular' => $row[$cellPhoneIndex] ?? null,
                    'telefone_fixo' => $row[$landlineIndex] ?? null,
                    'whatsapp' => $row[$whatsappIndex] ?? null,
                ]);
            }

            // Retorna resposta de sucesso
            return response()->json([
                'success' => true,
                'message' => 'Dados importados e salvos com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            // Captura erros e retorna resposta apropriada
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar o arquivo: ' . $e->getMessage(),
            ], 400);
        }
    }
  
}
