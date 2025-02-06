<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ApplicationSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'adm@adm.com',
            'password' => '12345678',
            'role'=> 'Cradt',
        ]);

        User::factory()->create([
            'name' => 'Test Aluno',
            'email' => 'aluno@aluno.com',
            'password' => '12345678',
            'role'=> 'Aluno',
        ]);

        $this->call([
            ApplicationSeeder::class
        ]);
    }
}
