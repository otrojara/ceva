<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'prueba']);

        $permission = Permission::create(['name' => 'Crear Usuario']);
        $permission = Permission::create(['name' => 'Leer Usuario']);
        $permission = Permission::create(['name' => 'Actualizar Usuario']);
        $permission = Permission::create(['name' => 'Eliminar Usuario']);

    }
}
