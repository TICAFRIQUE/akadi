<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioWhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            env('TWILIO_ACCOUNT_SID'),
            env('TWILIO_AUTH_TOKEN')
        );
    }

    public function sendMessage($to, $message)
    {
        // S'assure que le numéro a bien le "+" devant
        if (strpos($to, '+') !== 0) {
            $to = '+' . $to;
        }

        $twilio = new Client(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
        $twilio->messages->create("whatsapp:+225779613593", [
            'from' => 'whatsapp:+14155238886',
            'body' => '✅ Test depuis Artisan Laravel'
        ]);
    }
}
