<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('users')->delete();

        DB::table('users')->insert(array(
            0 =>
            array(
                'id' => 1965711134,
                'id_socialite' => NULL,
                'name' => 'zoolouk',
                'phone' => '0142855584',
                'email' => 'admin@zoolouk.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$pILLnRCW8P.K7ROMyotr0OIP0Vus9U3p5jvx1xQo9Ut5aklqwGDSC',
                'avatar' => NULL,
                'role' => 'administrateur',
                'shop_name' => 'zoolouk',
                'localisation' => 'abidjan',
                'remember_token' => NULL,
                'created_at' => '2023-12-05 21:03:48',
                'updated_at' => '2023-12-05 21:04:38',
            ),
          
           
            3 =>
            array(
                'id' => 8733553088,
                'id_socialite' => NULL,
                'name' => 'diomande emmanuel',
                'phone' => '0140782821',
                'email' => 'emmanuel.diomande@outlouk.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$H6wSb9.1FhwDaX1E9Vs9Ou1Bxxoi8kscecR0g748DEVw0lxH7cvNK',
                'avatar' => NULL,
                'role' => 'administrateur',
                'shop_name' => 'zoolouk',
                'localisation' => 'abidjan',
                'remember_token' => NULL,
                'created_at' => '2023-11-28 21:52:36',
                'updated_at' => '2023-11-28 21:52:36',
            ),
            4 =>
            array(
                'id' => 16476549411,
                'id_socialite' => NULL,
                'name' => 'Drissa',
                'phone' => '0758100958',
                'email' => 'drissa@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$6TMU6x0iNbSgRz3LdCfoBuMktIXolnlQygyoCe5.XmESXnSB.FgcG',
                'avatar' => NULL,
                'role' => 'fournisseur',
                'shop_name' => 'drissa shop',
                'localisation' => 'Abidjan, Adjame',
                'remember_token' => NULL,
                'created_at' => '2023-12-12 17:37:47',
                'updated_at' => '2023-12-12 17:38:43',
            ),
            5 =>
            array(
                'id' => 16896353041,
                'id_socialite' => NULL,
                'name' => 'alex',
                'phone' => '0779613599',
                'email' => 'alexkouamelan9@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$NSXhW3GUjBVrchNiRxzB/uvFwqAAS0HZK0v8j95UKd8rsC7zzUriO',
                'avatar' => NULL,
                'role' => NULL,
                'shop_name' => NULL,
                'localisation' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-01-31 23:00:04',
                'updated_at' => '2024-01-31 23:00:04',
            ),
            6 =>
            array(
                'id' => 17897826631,
                'id_socialite' => NULL,
                'name' => 'Kouamelan tanoh alex',
                'phone' => '0779613593',
                'email' => 'alexkouamelan96@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$GeQ3LUR.3l6vH7CE5xpYGecwN53dzdPWx0zkp5z5m4gkJjt926r.y',
                'avatar' => NULL,
                'role' => 'administrateur',
                'shop_name' => 'zoolouk',
                'localisation' => 'abidjan',
                'remember_token' => NULL,
                'created_at' => '2023-11-28 21:56:11',
                'updated_at' => '2023-12-03 19:40:44',
            ),
        ));
    }
}
