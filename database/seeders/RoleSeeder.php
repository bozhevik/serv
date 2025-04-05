<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем тестового пользователя, если его нет
        if (!User::find(1)) {
            User::create([
                'id' => 1,
                'username' => 'Seedadmin',
                'email' => 'seedadmin@example.com',
                'password' => Hash::make('Admin123!')
            ]);
        }

        // Сначала создаем роли
        $adminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator role',
            'code' => 'ADMIN',
            'created_by' => 1
        ]);
        Role::create(['name' => 'User', 'description' => 'User role', 'code' => 'USER', 'created_by' => 1]);
        Role::create(['name' => 'Guest', 'description' => 'Guest role', 'code' => 'GUEST', 'created_by' => 1]);

        // Теперь прикрепляем роль Admin к пользователю
        $user = User::find(1);
        $user->roles()->attach($adminRole->id);
    }
}
