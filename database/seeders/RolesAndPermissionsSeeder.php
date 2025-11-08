<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Сбрасываем кэш ролей и пермишенов
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Создаем роль 'manager'
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);

        // (Опционально) Создаем админа и даем ему роль
        $adminUser = User::factory()->create([
            'name' => 'Admin Manager',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin') // Не забудьте поменять!
        ]);

        $adminUser->assignRole($managerRole);
    }
}
