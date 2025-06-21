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
            'name' => 'CRADTuser',
            'username' => 'CRADTuser',
            'email' => 'adm@adm.com',
            'password' => '12345678',
            'cpf' => '123.456.789-00',
            'rg' => '1234567',
            'matricula' => 'CRADT002',
            'role'=> 'Cradt',
        ]);

        User::factory()->create([
            'name' => 'ALUNOuser',
            'username' => 'ALUNOuser',
            'email' => 'aluno@aluno.com',
            'password' => '12345678',
            'cpf' => '987.654.321-00',
            'rg' => '17654321',
            'matricula' => '20231INFOI0001',
            'role'=> 'Aluno',
        ]);

        User::factory()->create([
            'name' => 'MANAGERuser',
            'username' => 'MANAGERuser',
            'email' => 'manager@manager.com',
            'password' => '12345678',
            'cpf' => '725.426.889-01',
            'rg' => '9235527',
            'matricula' => 'CRADT001',
            'role'=> 'Manager',
        ]);

        User::factory()->create([
            'name' => 'TEACHERuser',
            'username' => 'PROFuser',
            'email' => 'prof@prof.com',
            'password' => '12345678',
            'cpf' => '725.426.889-02',
            'rg' => '9235528',
            'matricula' => 'PROF001',
            'role'=> 'Professor',
        ]);

        User::factory()->create([
            'name' => 'COORDINATORuser',
            'username' => 'COORDuser',
            'email' => 'coord@coord.com',
            'password' => '12345678',
            'cpf' => '725.426.889-03',
            'rg' => '9235529',
            'matricula' => 'COORD001',
            'role'=> 'Coordenador',
        ]);

        $this->call([  
            ApplicationSeeder::class,
            //EventSeeder::class
        ]);
    }
}
