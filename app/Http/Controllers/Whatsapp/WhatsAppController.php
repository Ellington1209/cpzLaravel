<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Whatsapp\WhatsAppLog;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function sendMessagesToAll()
    {
        try {
            // Busca todos os usuários no banco
            $users = User::all();

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum usuário encontrado no banco de dados.',
                ]);
            }

            $results = [];
            $message = "Olá! Esta é uma mensagem enviada pelo sistema.";

            foreach ($users as $user) {
                if ($user->telefone_celular) {
                    $formattedNumber = '55' . $user->telefone_celular;
                    $response = $this->whatsAppService->sendMessage($formattedNumber, $message);

                    $status = $response['success'] ? 'Enviado' : 'Erro';
                    $details = $response['response'] ?? $response['details'];

                    // Salva o log no banco de dados
                    WhatsAppLog::create([
                        'user_name' => $user->name,
                        'phone_number' => $formattedNumber,
                        'message' => $message,
                        'status' => $status,
                        'details' => json_encode($details), // Armazena os detalhes como JSON
                    ]);

                    $results[] = [
                        'user' => $user->name,
                        'number' => $formattedNumber,
                        'status' => $status,
                        'details' => $details,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Mensagens processadas.',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar mensagens.',
                'details' => $e->getMessage(),
            ]);
        }
    }
}
