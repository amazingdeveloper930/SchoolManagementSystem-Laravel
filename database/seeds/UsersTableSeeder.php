<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* $user = new User();
        $user->name = 'Joel';
        $user->email = 'taujomi17@gmail.com';
        $user->password = bcrypt('secret');
        $user->save();

        $user->assignRole('super_admin'); */

        $user = new User();
        $user->name = 'Roderick Castillo';
        $user->email = 'rdk@leo.com.pa';
        $user->password = bcrypt('123456');
        $user->save();

        $user->assignRole('super_admin');

        /* $user = new User();
        $user->name = 'Oswald';
        $user->email = 'oswaldale04@gmail.com';
        $user->password = bcrypt('secret');
        $user->save();

        $user->assignRole('super_admin');

        $user = new User();
        $user->name = 'Demo';
        $user->email = 'demo@gmail.com';
        $user->password = bcrypt('123456');
        $user->save();

        $user->assignRole('super_admin'); */

    }
}
