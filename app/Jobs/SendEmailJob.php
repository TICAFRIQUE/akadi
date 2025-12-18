<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $to;
    public $subject;
    public $view;
    public $data;
    public $from;
    public $fromName;

    /**
     * Create a new job instance.
     */
    public function __construct($to, $subject, $view, $data = [], $from = 'info@akadi.ci', $fromName = 'AKADI Restaurant')
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
        $this->from = $from;
        $this->fromName = $fromName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $mail = new PHPMailer(true);

            // Email SMTP Settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.akadi.ci';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@akadi.ci';
            $mail->Password = 'S$UBfu.8s(#z';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom($this->from, $this->fromName);
            $mail->addAddress($this->to);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $this->subject;
            $mail->Body = view($this->view, $this->data)->render();

            $mail->send();

            Log::info('Email envoyé avec succès via queue', [
                'to' => $this->to,
                'subject' => $this->subject
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi email via queue: ' . $e->getMessage(), [
                'to' => $this->to,
                'subject' => $this->subject
            ]);

            // Relancer le job en cas d'échec (max 3 tentatives)
            if ($this->attempts() < 3) {
                $this->release(60); // Réessayer après 60 secondes
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Échec définitif de l\'envoi d\'email', [
            'to' => $this->to,
            'subject' => $this->subject,
            'error' => $exception->getMessage()
        ]);
    }
}
