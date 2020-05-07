<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Contract;

class ContractsTableSeeder extends Seeder
{
    public function run()
    {
        $contract = new Contract();
        $contract->enrollment_grade = '7°';
        $contract->enrollment_bachelor = 'Basico General';
        $contract->enrollment_cost = 100;
        $contract->year = 2018;
        $contract->student_id = 1;
        $contract->user_id = 1;
        $contract->request = '010-010';
        $contract->date = now();
        $contract->observation = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad, perferendis!';
        $contract->annuity_cost = 1400;
        $contract->receipt = '[]';
        $contract->save();


        $contract = new Contract();
        $contract->enrollment_grade = '8°';
        $contract->enrollment_bachelor = 'Basico General';
        $contract->enrollment_cost = 107;
        $contract->year = 2019;
        $contract->student_id = 1;
        $contract->user_id = 1;
        $contract->request = '010-011';
        $contract->date = now();
        $contract->observation = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci, reprehenderit!';
        $contract->annuity_cost = 1649.5;
        $contract->receipt = '[]';
        $contract->save();
    }
}
