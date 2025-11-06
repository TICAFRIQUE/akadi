<?php

namespace App\Services;

class smsService
{
    protected $host = "193.105.74.59";
    protected $path = "/api/sendsms/xml";

    public function send($username, $password, $sender, $message, $flash, $recipients, $type = "longSMS", $bookmark = "")
    {
        $xmlRecipients = '';
        foreach (explode(',', $recipients) as $gsm) {
            $xmlRecipients .= "<gsm>{$gsm}</gsm>";
        }

        $xmlData = "
        <SMS>
            <authentification>
                <username>{$username}</username>
                <password>{$password}</password>
            </authentification>
            <message>
                <sender>{$sender}</sender>
                <text>{$message}</text>
                <flash>{$flash}</flash>
                <type>{$type}</type>
                <bookmark>{$bookmark}</bookmark>
            </message>
            <recipients>{$xmlRecipients}</recipients>
        </SMS>";

        $postData = 'XML=' . $xmlData;

        $fp = fsockopen($this->host, 80, $errno, $errstr, 10);
        if (!$fp) {
            return [
                'status' => 'error',
                'message' => "Erreur connexion: $errstr ($errno)"
            ];
        }

        $request = "POST {$this->path} HTTP/1.1\r\n";
        $request .= "Host: {$this->host}\r\n";
        $request .= "User-Agent: Laravel SMS Client\r\n";
        $request .= "Content-Type: text/xml\r\n";
        $request .= "Content-Length: " . strlen($postData) . "\r\n";
        $request .= "Connection: close\r\n\r\n";
        $request .= $postData;

        fwrite($fp, $request);

        $response = '';
        while (!feof($fp)) {
            $response .= fgets($fp, 128);
        }

        fclose($fp);

        // Nettoyer la réponse
        $parts = preg_split("/(\r\n\r\n)/", $response);
        $header = $parts[0] ?? '';
        $content = $parts[1] ?? '';

        if (strpos($header, "Transfer-Encoding: chunked") !== false) {
            $lines = preg_split("/(\r\n)/", $content);
            for ($i = 0; $i < count($lines); $i++) {
                if ($i == 0 || $i % 2 == 0) {
                    $lines[$i] = "";
                }
            }
            $content = implode("", $lines);
        }

        return [
            'status' => 'ok',
            'response' => trim($content)
        ];
    }
}
