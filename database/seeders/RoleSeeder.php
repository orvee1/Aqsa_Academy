<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(['slug' => 'admin'],  ['name' => 'Admin',  'status' => true]);
        Role::updateOrCreate(['slug' => 'editor'], ['name' => 'Editor', 'status' => true]);
        Role::updateOrCreate(['slug' => 'staff'],  ['name' => 'Staff',  'status' => true]);
    }
}
