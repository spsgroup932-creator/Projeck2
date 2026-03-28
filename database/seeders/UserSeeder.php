<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin custom
        User::create([
            'name' => 'Arul',
            'email' => 'arul',
            'password' => Hash::make('arul'),
            'role' => 'super admin',
        ]);

        $roles = ['admin cabang', 'admin devisi', 'admin', 'user'];

        foreach ($roles as $role) {
            User::create([
                'name' => ucwords($role),
                'email' => str_replace(' ', '', $role) . '@example.com',
                'password' => Hash::make('password'),
                'role' => $role,
            ]);
        }
    }
}
