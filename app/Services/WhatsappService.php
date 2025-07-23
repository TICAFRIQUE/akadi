<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    public function sendTemplateMessage($to, $templateName, $languageCode = 'fr_FR', $params = [])
    {
        $token = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        $components = [
            [
                "type" => "body",
                "parameters" => array_map(function ($param) {
                    return [
                        "type" => "text",
                        "text" => $param
                    ];
                }, $params)
            ]
        ];

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => ["code" => $languageCode],
                "components" => $components
            ]
        ];

        $response = Http::withToken($token)
            ->post("https://graph.facebook.com/v22.0/{$phoneNumberId}/messages", $payload);

        return $response->json();
    }
}
