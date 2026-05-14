<?php

namespace Database\Seeders;

use App\Models\ApplicationRequest;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ApplicationSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(['email' => 'adm@adm.com'], [
            'name' => 'CRADTuser',
            'username' => 'CRADTuser',
            'password' => Hash::make('12345678'),
            'cpf' => '123.456.789-00',
            'rg' => '1234567',
            'matricula' => 'CRADT002',
            'role'=> 'Cradt',
        ]);

        User::updateOrCreate(['email' => 'aluno@aluno.com'], [
            'name' => 'ALUNOuser',
            'username' => 'ALUNOuser',
            'password' => Hash::make('12345678'),
            'cpf' => '987.654.321-00',
            'rg' => '17654321',
            'matricula' => '20231INFOI0001',
            'role'=> 'Aluno',
        ]);

        User::updateOrCreate(['email' => 'diretor@diretor.com'], [
            'name' => 'Diretor Geral',
            'username' => 'diretor',
            'password' => Hash::make('12345678'),
            'cpf' => '725.426.889-02',
            'rg' => '9235528',
            'matricula' => 'DIR001',
            'role'=> 'Diretor Geral',
        ]);

        if (ApplicationRequest::doesntExist()) {
            $this->call([
                ApplicationSeeder::class,
                //EventSeeder::class
            ]);
        }
    }
}
