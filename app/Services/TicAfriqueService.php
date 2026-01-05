<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TicAfriqueService
{
    /**
     * Envoyer un SMS via l'API TIC Afrique
     */
    private function sendSms($numero, $message)
    {
        $apiKey = 'sk_a8ba45ac5f5037ae949865ccc511b1cf88238bb33a9f620108a386eecdd283ba';
        $url = 'https://sms.ticafrique.ci/api/v1/sms/send';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'to' => $numero,
            'message' => $message,
            'sender_id' => 'AKADI'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return [
            'http_code' => $httpCode,
            'response' => $response
        ];
    }

    /**
     * Envoyer un SMS de confirmation de commande
     */
    public function sendOrderConfirmationSms($order)
    {
        // Vérifier si le client a un numéro
        if (!$order->user || !$order->user->phone) {
            Log::error('SMS non envoyé : pas de numéro', ['commande_id' => $order->id]);
            return;
        }

        // Préparer le numéro (ajouter +225 si besoin)
        $numero = '0779613593';
        // $numero = ltrim($numero, '0');
        $numero = '+225' . $numero;

        // Le message à envoyer
        $message = "Bonjour " . $order->user->name .
            ", Votre commande #" . $order->code . " a été confirmée avec succès. " .
            "Vous serez livré dans peu de temps. Pour toute urgence, contactez-nous au 0758838338. " .
            "Merci - AKADI";

        // Envoyer le SMS
        $result = $this->sendSms($numero, $message);

        // Log du résultat
        Log::info('SMS envoyé', [
            'commande_id' => $order->id,
            'numero' => $numero,
            'http_code' => $result['http_code'],
            'response' => $result['response']
        ]);

        //response json pour api

        return response()->json([
            'commande_id' => $order->id,
            'numero' => $numero,
            'http_code' => $result['http_code'],
            'response' => $result['response']
        ], 200);
    }
}
