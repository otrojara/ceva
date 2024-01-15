<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Diego Jara Montenegro',
            'rut' => '19.092.773-3',
            'email' => 'djaramontenegro@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('admin');
    }
}
