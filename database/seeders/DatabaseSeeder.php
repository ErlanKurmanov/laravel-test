<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Вызываем сидер Ролей и Прав (из прошлого шага)
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Получаем роль 'manager'
        $managerRole = Role::findByName('manager');

        // 3. Создаем тестового Менеджера
        User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@crm.com',
            'password' => bcrypt('password'), // Пароль: password
        ])->assignRole($managerRole);

        // 4. Создаем 20 заявок (с авто-созданием клиентов)
        Ticket::factory(20)->create();

        // 5. (Опционально) Создаем клиента с известными данными для тестов
        Customer::factory()->create([
            'name' => 'Known Customer',
            'email' => 'customer@test.com',
            'phone' => '+12223334444',
        ]);
    }
}
