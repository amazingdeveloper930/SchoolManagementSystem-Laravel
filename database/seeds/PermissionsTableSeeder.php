<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permisos Modulo tablero
        Permission::create(['name' => 'board.index']);
        Permission::create(['name' => 'board.store_edit']);
        Permission::create(['name' => 'board.cancel']);

        // Permisos Modulo estudiantes
        Permission::create(['name' => 'students.index']);
        Permission::create(['name' => 'students.store_edit']);
        Permission::create(['name' => 'students.cancel']);

        // Permisos Modulo pagos
        Permission::create(['name' => 'payments.index']);
        Permission::create(['name' => 'payments.store_edit']);
        Permission::create(['name' => 'payments.cancel']);

        // Permisos Modulo reportes
        Permission::create(['name' => 'reports.index']);
        Permission::create(['name' => 'reports.store_edit']);
        Permission::create(['name' => 'reports.cancel']);

        // Permisos Modulo gestion de costos
        Permission::create(['name' => 'costs.index']);
        Permission::create(['name' => 'costs.store_edit']);
        Permission::create(['name' => 'costs.cancel']);

        //Rol SuperAdmin
        $admin = Role::create(['name' => 'super_admin']);
        $admin->givePermissionTo([
            'board.index',
            'board.store_edit',
            'board.cancel',

            'students.index',
            'students.store_edit',
            'students.cancel',

            'payments.index',
            'payments.store_edit',
            'payments.cancel',

            'reports.index',
            'reports.store_edit',
            'reports.cancel',

            'costs.index',
            'costs.store_edit',
            'costs.cancel',
        ]);

    }
}
