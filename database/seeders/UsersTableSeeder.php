<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 17897826631,
                'id_socialite' => NULL,
                'name' => 'Kouamelan tanoh alex',
                'phone' => '0779613593',
                'email' => 'alexkouamelan96@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$GeQ3LUR.3l6vH7CE5xpYGecwN53dzdPWx0zkp5z5m4gkJjt926r.y',
                'avatar' => NULL,
                'role' => 'developpeur',
                'shop_name' => NULL,
                'localisation' => NULL,
                'remember_token' => NULL,
                'created_at' => '2023-11-28 21:56:11',
                'updated_at' => '2023-12-03 19:40:44',
            ),
        ));
        
        
    }
}