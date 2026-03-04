<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'email' => 'developpeur@gmail.com',
                'email_verified_at' => NULL,
                'password' => Hash::make('developpeur'),
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