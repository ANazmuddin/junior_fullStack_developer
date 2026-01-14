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

    // (Opsional) Kita buat 1 User Admin sekalian untuk tes login nanti
    \App\Models\User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'), // passwordnya 'password'
        'role_id' => 1, // Asumsi ID 1 adalah Admin
    ]);
}
}
