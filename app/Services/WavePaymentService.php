<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WavePaymentService
{
    protected $apiKey;
    protected $apiUrl;
    protected $successUrl;
    protected $errorUrl;

    public function __construct()
    {
        $this->apiKey = config('services.wave.api_key');
        $this->apiUrl = config('services.wave.api_url');
        $this->successUrl = config('services.wave.success_url');
        $this->errorUrl = config('services.wave.error_url');
    }

    /**
     * Créer une session de paiement Wave
     * 
     * @param float $amount Montant à payer
     * @param string $currency Devise (par défaut XOF)
     * @param string|null $orderId ID de la commande pour tracking
     * @return array
     * @throws Exception
     */
    public function createCheckoutSession($amount, $currency = 'XOF', $orderId = null)
    {
        try {
            // Construire les URLs avec l'ID de commande si fourni
            $successUrl = $this->successUrl . ($orderId ? "?order_id={$orderId}" : '');
            $errorUrl = $this->errorUrl . ($orderId ? "?order_id={$orderId}" : '');

            $checkoutParams = [
                'amount' => (string) $amount,
                'currency' => $currency,
                'error_url' => $errorUrl,
                'success_url' => $successUrl,
            ];

            // Log de la requête pour debugging
            Log::info('Wave Payment Request', [
                'params' => $checkoutParams,
                'order_id' => $orderId
            ]);

            // Faire l'appel à l'API Wave
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl, $checkoutParams);

            // Vérifier si la requête a réussi
            if ($response->failed()) {
                Log::error('Wave Payment Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Erreur lors de la création de la session de paiement Wave');
            }

            $checkoutSession = $response->json();

            // Vérifier que la réponse contient l'URL de redirection
            if (!isset($checkoutSession['wave_launch_url'])) {
                Log::error('Wave Payment Missing URL', ['response' => $checkoutSession]);
                throw new Exception('URL de paiement Wave non disponible');
            }

            // Log du succès
            Log::info('Wave Payment Session Created', [
                'order_id' => $orderId,
                'session_id' => $checkoutSession['id'] ?? null
            ]);

            return [
                'success' => true,
                'wave_launch_url' => $checkoutSession['wave_launch_url'],
                'session_id' => $checkoutSession['id'] ?? null,
                'checkout_session' => $checkoutSession
            ];

        } catch (Exception $e) {
            Log::error('Wave Payment Exception', [
                'message' => $e->getMessage(),
                'order_id' => $orderId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier le statut d'une session de paiement
     * 
     * @param string $sessionId ID de la session Wave
     * @return array
     */
    public function checkPaymentStatus($sessionId)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->get("{$this->apiUrl}/{$sessionId}");

            if ($response->failed()) {
                throw new Exception('Impossible de vérifier le statut du paiement');
            }

            return [
                'success' => true,
                'data' => $response->json()
            ];

        } catch (Exception $e) {
            Log::error('Wave Payment Status Check Error', [
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
