<?php

use Illuminate\Database\Seeder;
use App\Student;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student = new Student();
        $student->personal_id = '4-425-235';
        $student->name = 'Joel Villanueva';
        $student->phone = '298327462';
        $student->status = 1;
        $student->email = 'taujomi17@gmail.com';
        $student->attendant = 'Joel Villanueva';
        $student->save();
        
    }
}
