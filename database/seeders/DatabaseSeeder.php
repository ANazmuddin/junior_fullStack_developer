<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // Panggil RoleSeeder
    $this->call(RoleSeeder::class);

    \App\Models\User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role_id' => 1,
    ]);
}
}
