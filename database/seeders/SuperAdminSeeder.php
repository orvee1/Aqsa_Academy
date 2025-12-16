<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'orvee.imrul32@gmail.com'],
            [
                'name' => 'Super Admin',
                'phone' => '01617794123',
                'password' => Hash::make('123456'),
                'is_super_admin' => true,
                'role_id' => null,
            ]
        );
    }
}
