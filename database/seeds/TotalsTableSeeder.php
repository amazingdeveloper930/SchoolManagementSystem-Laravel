<?php

use Illuminate\Database\Seeder;
use App\Total;

class TotalsTableSeeder extends Seeder
{
    public function run()
    {
        
    	$total = new Total();
    	$total->balance = 1200;
    	$total->contract_id = 1;
    	$total->save();

		$total = new Total();
    	$total->balance = 1200;
    	$total->contract_id = 2;
    	$total->save();
    }
}
