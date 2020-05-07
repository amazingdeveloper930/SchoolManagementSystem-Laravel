<?php

use Illuminate\Database\Seeder;
use App\Enrollment;

class EnrollmentsTableSeeder extends Seeder
{
    public function run()
    {
        $enrollment = new Enrollment();
        $enrollment->grade = '7mo';
        $enrollment->bachelor = 'Ciencias';
        $enrollment->cost = 135;
        $enrollment->save();

        $enrollment = new Enrollment();
        $enrollment->grade = '6to';
        $enrollment->bachelor = 'Ciencias';
        $enrollment->cost = 100;
        $enrollment->save();

        $enrollment = new Enrollment();
        $enrollment->grade = '3er';
        $enrollment->bachelor = 'Ciencias';
        $enrollment->cost = 150;
        $enrollment->save();
    }
}
