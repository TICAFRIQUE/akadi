<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\SendEmailJob;

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

        // Email pour anniversaires à venir (dispatch en queue)
        if (count($user_upcoming_birthday) > 0) {
            SendEmailJob::dispatch(
                'Restaurantakadi@gmail.com',
                '🎂 Anniversaire Client - AKADI',
                'emails.birthday_notification',
                ['message' => 'C\'est bientôt l\'anniversaire d\'un de vos clients !'],
                'info@akadi.ci',
                'AKADI Restaurant'
            );
            $this->info('Job anniversaire à venir dispatché !');
        }

        // Email pour anniversaires du jour (dispatch en queue)
        if (count($user_birthday) > 0) {
            SendEmailJob::dispatch(
                'Restaurantakadi@gmail.com',
                '🎂 Anniversaire Client - AKADI',
                'emails.birthday_notification',
                ['message' => 'C\'est aujourd\'hui l\'anniversaire d\'un de vos clients !'],
                'info@akadi.ci',
                'AKADI Restaurant'
            );
            $this->info('Job anniversaire du jour dispatché !');
        }

        if (count($user_upcoming_birthday) == 0 && count($user_birthday) == 0) {
            $this->info('Aucun anniversaire à signaler aujourd\'hui.');
        }
    }
}
