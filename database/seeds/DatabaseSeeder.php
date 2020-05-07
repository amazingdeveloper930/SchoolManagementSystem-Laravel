<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(AnnuitysTableSeeder::class);
        $this->call(EnrollmentsTableSeeder::class);

        // $this->call(ContractsTableSeeder::class);
        // $this->call(FeesTableSeeder::class);
        // $this->call(TotalsTableSeeder::class);
    }
}
