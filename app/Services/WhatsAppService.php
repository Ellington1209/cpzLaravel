<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'http://147.182.251.158:8080'; // URL base da API
        $this->apiKey = env('WHATSAPP_API_KEY'); // Defina sua API Key no .env
    }

    public function sendMessage($number, $message, $instanceName = 'tom')
    {
        try {
            $url = "{$this->baseUrl}/message/sendText/{$instanceName}";

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'number' => $number,
                'textMessage' => [
                    'text' => $message,
                ],
                'options' => [
                    'linkPreview' => false,
                    'presence' => 'composing',
                    'delay' => 0,
                ],
            ]);

            return [
                'success' => $response->status() === 201,
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'details' => $e->getMessage(),
            ];
        }
    }
}
