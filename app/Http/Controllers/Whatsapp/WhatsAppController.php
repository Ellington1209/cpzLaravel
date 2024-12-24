<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\Membro\Membro;
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

    public function sendMessagesToAll(Request $request)
    {
        try {
            // Busca todos os usuários no banco
            $users = Membro::all();

            $validatedData = $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum usuário encontrado no banco de dados.',
                ]);
            }

            $results = [];
            $messageTemplate = $validatedData['message'];

            foreach ($users as $user) {
                if ($user->telefone_celular) {
                    // Substitui @nome pelo nome_crente do usuário
                    $personalizedMessage = str_replace('@nome', $user->nome_crente, $messageTemplate);

                    $formattedNumber = '55' . $user->telefone_celular;
                    $response = $this->whatsAppService->sendMessage($formattedNumber, $personalizedMessage);

                    $status = $response['success'] ? 'Enviado' : 'Erro';
                    $details = $response['response'] ?? $response['details'];

                    // Salva o log no banco de dados
                    WhatsAppLog::create([
                        'user_name' => $user->name,
                        'phone_number' => $formattedNumber,
                        'message' => $personalizedMessage,
                        'status' => $status,
                        'details' => json_encode($details),
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



    public function sendMedia(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'mediaType' => 'required|string|in:image,video,document,audio',
                'fileName' => 'required|string',
                'caption' => 'nullable|string|max:1000',
                'media' => 'required|file|mimes:jpg,png,mp4,pdf,mp3|max:2048', // Limite de tamanho e tipos permitidos
            ]);

            // Codifica o arquivo em Base64
            $mediaBase64 = base64_encode(file_get_contents($request->file('media')->path()));

            // Busca todos os usuários no banco
            $users = User::all();

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum usuário encontrado no banco de dados.',
                ]);
            }

            $results = [];

            foreach ($users as $user) {
                if ($user->telefone_celular) {
                    $formattedNumber = '55' . $user->telefone_celular;
                    $response = $this->whatsAppService->sendMediaMessage(
                        $formattedNumber,
                        $validatedData['mediaType'],
                        $validatedData['fileName'],
                        $validatedData['caption'] ?? '',
                        $mediaBase64
                    );

                    $status = $response['success'] ? 'Enviado' : 'Erro';
                    $details = $response['response'] ?? $response['details'];

                    // Salva o log no banco de dados
                    WhatsAppLog::create([
                        'user_name' => $user->name,
                        'phone_number' => $formattedNumber,
                        'message' => $validatedData['caption'] ?? '',
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
                'message' => 'Mensagens de mídia processadas.',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar mensagens de mídia.',
                'details' => $e->getMessage(),
            ]);
        }
    }
}
