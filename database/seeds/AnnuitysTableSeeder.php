<?php

use Illuminate\Database\Seeder;
use App\Annuity;

class AnnuitysTableSeeder extends Seeder
{
    public function run()
    {
        $annuity = new Annuity();
        $annuity->year = 2019;
    	$annuity->cost = 1200;
    	$annuity->discount = 75;
        $annuity->second_month = 3;
        $annuity->maximum_date = now();
        $annuity->save();

        $annuity = new Annuity();
        $annuity->year = 2018;
        $annuity->cost = 1200;
        $annuity->discount = 75;
        $annuity->second_month = 3;
        $annuity->maximum_date = now();
        $annuity->save();
    }
}
