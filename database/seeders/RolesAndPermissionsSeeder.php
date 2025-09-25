<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'ver usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'borrar usuarios']);
        Permission::create(['name' => 'ver roles']);
        Permission::create(['name' => 'crear roles']);
        Permission::create(['name' => 'editar roles']);
        Permission::create(['name' => 'borrar roles']);
        Permission::create(['name' => 'ver permisos']);
        Permission::create(['name' => 'asignar permisos']);
        Permission::create(['name' => 'ver todos los informes']);
        Permission::create(['name' => 'eliminar informes terminados']);
        Permission::create(['name' => 'mis informes']);
        Permission::create(['name' => 'crear informes']);
        Permission::create(['name' => 'editar informes']);
        Permission::create(['name' => 'borrar informes en prodiccion']);
        Permission::create(['name' => 'historico de informes']);
        Permission::create(['name' => 'agregar contenido']);
        Permission::create(['name' => 'editar contenido']);
        Permission::create(['name' => 'borrar contenido']);
        Permission::create(['name' => 'ver informe pdf']);
        Permission::create(['name' => 'descargar informe pdf']);
        Permission::create(['name' => 'finalizar informe']);

        // Crear roles y asignar permisos
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $analista = Role::create(['name' => 'Analista']);
        $analista->givePermissionTo([
            'mis informes',
            'crear informes',
            'editar informes',
            'borrar informes en prodiccion',
            'historico de informes',
            'agregar contenido',
            'editar contenido',
            'borrar contenido',
            'ver informe pdf',
            'descargar informe pdf',
            'finalizar informe',
        ]);
    }
}
