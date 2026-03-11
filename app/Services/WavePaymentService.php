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
        
        // Valider la configuration
        if (empty($this->apiKey)) {
            Log::error('Wave API Key manquante');
        }
        if (empty($this->apiUrl)) {
            Log::error('Wave API URL manquante');
        }
        if (empty($this->successUrl) || empty($this->errorUrl)) {
            Log::warning('Wave callback URLs non configurées correctement', [
                'success_url' => $this->successUrl,
                'error_url' => $this->errorUrl
            ]);
        }
    }

    /**
     * Créer une session de paiement Wave
     * 
     * @param float $amount Montant à payer
     * @param string $currency Devise (par défaut XOF)
     * @param string|null $reference Référence de transaction pour tracking
     * @return array
     * @throws Exception
     */
    public function createCheckoutSession($amount, $currency = 'XOF', $reference = null)
    {
        try {
            // Construire les URLs avec la référence si fournie
            $successUrl = $this->successUrl . ($reference ? "?ref={$reference}" : '');
            $errorUrl = $this->errorUrl . ($reference ? "?ref={$reference}" : '');

            $checkoutParams = [
                'amount' => (string) $amount,
                'currency' => $currency,
                'error_url' => $errorUrl,
                'success_url' => $successUrl,
            ];

            // Log de la requête pour debugging
            Log::info('Wave Payment Request', [
                'params' => $checkoutParams,
                'reference' => $reference
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
                $errorBody = $response->body();
                $statusCode = $response->status();
                
                Log::error('Wave Payment API Error', [
                    'status' => $statusCode,
                    'body' => $errorBody,
                    'headers' => $response->headers(),
                    'reference' => $reference
                ]);
                
                // Essayer de parser le message d'erreur de Wave
                $errorMessage = 'Erreur lors de la création de la session de paiement Wave';
                try {
                    $errorData = $response->json();
                    if (isset($errorData['message'])) {
                        $errorMessage .= ': ' . $errorData['message'];
                    } else if (isset($errorData['error'])) {
                        $errorMessage .= ': ' . $errorData['error'];
                    }
                } catch (Exception $e) {
                    $errorMessage .= ' (Code: ' . $statusCode . ')';
                }
                
                throw new Exception($errorMessage);
            }

            $checkoutSession = $response->json();

            // Vérifier que la réponse contient l'URL de redirection
            if (!isset($checkoutSession['wave_launch_url'])) {
                Log::error('Wave Payment Missing URL', ['response' => $checkoutSession]);
                throw new Exception('URL de paiement Wave non disponible');
            }

            // Log du succès
            Log::info('Wave Payment Session Created', [
                'reference' => $reference,
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
                'reference' => $reference
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
