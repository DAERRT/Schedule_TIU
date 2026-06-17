<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Year;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Создание ролей
        Role::create([
            'name' => 'admin',
        ]);
        Role::create([
            'name' => 'redactor',
        ]);


        //Создание тестового пользователя
        User::create([
            'email' => 'test@tyuiu.ru',
            'password' => Hash::make('123456789'),
            'isPassChanged' => true,
        ]);

        
        //Привязка ролей к пользователю
        DB::table('user_role')->insert([
            ['id_users' => 1, 'id_roles' => 2],
        ]);

        //Создание тестовых годов
        Year::create([
            'year_start' => '2023',
            'year_end' => '2024',
        ]);

    }
}
