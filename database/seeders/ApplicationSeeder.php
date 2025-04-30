<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationRequest;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');

        $tipoRequisicaoOptions = [
            'Admissão por Transferência e Análise Curricular',
            'Ajuste de Matrícula Semestral',
            'Autorização para cursar disciplinas em outras Instituições de Ensino Superior',
            'Cancelamento de Matrícula',
            'Cancelamento de Disciplina',
            'Certificado de Conclusão',
            'Certidão - Autenticidade',
            'Complementação de Matrícula',
            'Cópia Xerox de Documento',
            'Declaração de Colação de Grau e Tramitação de Diploma',
            'Declaração de Matrícula ou Matrícula Vínculo',
            'Declaração de Monitoria',
            'Declaração para Estágio',
            'Diploma 1ªvia/2ªvia',
            'Dispensa da prática de Educação Física',
            'Declaração Tramitação de Diploma',
            'Ementa de disciplina',
            'Guia de Transferência',
            'Histórico Escolar',
            'Isenção de disciplinas cursadas',
            'Justificativa de falta(s) ou prova 2º chamada',
            'Matriz curricular',
            'Reabertura de Matrícula',
            'Reintegração ( ) Estágio ( ) Entrega do Relatório de Estágio ( ) TCC',
            'Reintegração para Cursar',
            'Solicitação de Conselho de Classe',
            'Trancamento de Matrícula',
            'Transferência de Turno',
            'Outros'
        ];

        // Opções de situação
        $situacaoOptions = [
            'Matriculado',
            'Graduado',
            'Desvinculado',
        ];

        for ($i = 0; $i < 500; $i++) {
            ApplicationRequest::create([
                'key' => Str::uuid(),
                'nomeCompleto' => $faker->name,
                'cpf' => $faker->cpf,
                'celular' => $faker->phoneNumber,
                'email' => $faker->firstName($faker->name) . '@discente.ifpe.edu.br',
                'rg' => $faker->rg,
                'orgaoExpedidor' => $faker->randomElement(['SSP', 'SDS', 'PM']),
                'campus' => $faker->city,
                'matricula' => $faker->randomNumber(8, true),
                'situacao' => $faker->randomElement($situacaoOptions),
                'curso' => $faker->randomElement(['Informática para Internet', 'Logística', 'Gestão da Qualidade', 'Administração', 'Tecnologia em Sistemas para Internet']),
                'periodo' => $faker->numberBetween(1, 6),
                'turno' => $faker->randomElement(['Manhã', 'Tarde']),
                'tipoRequisicao' => $faker->randomElement($tipoRequisicaoOptions),
                'observacoes' => $faker->sentence,
            ]);
        }
    }
}

// Não esquece de rodar: php artisan db:seed --class=ApplicationSeeder

?>
