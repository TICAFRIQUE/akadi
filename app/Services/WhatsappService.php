<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    /**
     * Envoyer un message WhatsApp
     *
     * @param string $to   Numéro du destinataire au format E.164 (ex: +2250700000000)
     * @param string $message
     * @return mixed
     */
    public function sendMessage(string $to, string $message)
    {
        return $this->twilio->messages->create(
            "whatsapp:" . $to,
            [
                "from" => config('services.twilio.from'),
                "body" => $message,
            ]
        );
    }
}
