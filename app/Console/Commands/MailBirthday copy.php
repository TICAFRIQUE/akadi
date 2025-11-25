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
        //
        //
        $user_upcoming_birthday = User::where('notify_birthday', 2)->OrWhere('notify_birthday', 1)->get(); // anniversaire a venir dans 2 jours
        $user_birthday = User::where('notify_birthday', 0)->get(); // anniversaire du jour 


        ###SEND MAIL TO ADMIN USER IF BIRTHDAY DATE ARRIVED ###

        if (count($user_upcoming_birthday) > 0) {
            $mail = new PHPMailer(true);
            // require base_path("vendor/autoload.php");

            /* Email SMTP Settings */
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.akadi.ci';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@akadi.ci';
            $mail->Password = 'S$UBfu.8s(#z';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
            $mail->addAddress('Restaurantakadi@gmail.com');

            $mail->isHTML(true);


            $mail->Subject = 'Anniversaire';
            $mail->Body =
                '<b> Bonjour Akadi, C\'est bientot l\'anniversaire de vos client  <br> Veuillez consulter les notifications sur le dashboard  <b>';

            $mail->send();
        }


        ///

        if (count($user_birthday) > 0) {
            $mail = new PHPMailer(true);
            // require base_path("vendor/autoload.php");

            /* Email SMTP Settings */
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.akadi.ci';
            $mail->SMTPAuth = true;
            $mail->Username = 'info@akadi.ci';
            $mail->Password = 'S$UBfu.8s(#z';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
            $mail->addAddress('Restaurantakadi@gmail.com');

            $mail->isHTML(true);


            $mail->Subject = 'Anniversaire';
            $mail->Body =
                '<b> Bonjour Akadi,Aujourd\'hui est l\'anniversaire de votre client  <br> Veuillez consulter les notifications sur le dashboard  <b>';

            $mail->send();
        }

        ######################################################### //new send mail with phpMailer

    }
}
