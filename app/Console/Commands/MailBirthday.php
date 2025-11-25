<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;

class MailBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_upcoming_birthday = User::where('notify_birthday', 2)->orWhere('notify_birthday', 1)->get();
        $user_birthday = User::where('notify_birthday', 0)->get();

        // Email pour anniversaires à venir
        if (count($user_upcoming_birthday) > 0) {
            $this->sendNotificationEmail('C\'est bientôt l\'anniversaire d\'un de vos clients !');
        }

        // Email pour anniversaires du jour
        if (count($user_birthday) > 0) {
            $this->sendNotificationEmail('C\'est aujourd\'hui l\'anniversaire d\'un de vos clients !');
        }
    }

    private function sendNotificationEmail($message)
    {
        $mail = new PHPMailer(true);

        try {
            /* Email SMTP Settings */
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.akadi.ci';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@akadi.ci';
            $mail->Password = 'S$UBfu.8s(#z';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('info@akadi.ci', 'AKADI Restaurant');
            $mail->addAddress('Restaurantakadi@gmail.com');

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = '🎂 Anniversaire Client - AKADI';

            // Utiliser le template simple
            $mail->Body = view('emails.birthday_notification', [
                'message' => $message
            ])->render();

            $mail->send();
            $this->info('Notification d\'anniversaire envoyée !');

        } catch (\Exception $e) {
            $this->error('Erreur envoi email: ' . $e->getMessage());
        }
    }
}
