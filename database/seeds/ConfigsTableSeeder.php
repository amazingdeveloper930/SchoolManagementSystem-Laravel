<?php

use Illuminate\Database\Seeder;
use App\Config;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Config::create([
        	'type' => 'mail',
        	'content' => 2,
        ]);

    }
}
