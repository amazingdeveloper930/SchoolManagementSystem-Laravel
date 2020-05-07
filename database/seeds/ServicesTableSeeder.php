<?php

use Illuminate\Database\Seeder;
use App\Service;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        $service = new Service();
        $service->description = 'Sigueduc';
        $service->cost = 150;
        $service->state = Service::REQUIRED;
        $service->save();

		$service = new Service();
        $service->description = 'Convalidad materia';
        $service->cost = 150;
        $service->state = Service::ACTIVE;
        $service->save();

        $service = new Service();
        $service->description = 'Retiro de asignatura';
        $service->cost = 150;
        $service->state = Service::ACTIVE;
        $service->save();

        $service = new Service();
        $service->description = 'Carta de culminacion';
        $service->cost = 150;
        $service->state = Service::ACTIVE;
        $service->save();
    }
}
