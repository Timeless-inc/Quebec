<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationRequest;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');

        $users = User::where('role', 'CRADT')->get();

        if ($users->isEmpty()) {
            $this->command->error('Nenhum usuário com cargo CRADT encontrado! O seeder precisa de servidores para requisições finalizadas/indeferidas.');
            return; // Encerrar o seeder se não houver usuários CRADT
        }
        
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

        $campusOptions = [
            'Recife',
            'Abreu e Lima',
            'Afogados da Ingazeira',
            'Barreiros',
            'Belo Jardim',
            'Cabo de Santo Agostinho',
            'Caruaru',
            'Garanhuns',
            'Igarassu',
            'Ipojuca',
            'Jaboatão dos Guararapes',
            'Olinda',
            'Palmares',
            'Paulista',
            'Pesqueira',
            'Vitória de Santo Antão'
        ];

        for ($i = 0; $i < 1000; $i++) {
            $statusOptions = ['pendente', 'em_andamento', 'finalizado', 'indeferido'];
            $status = $faker->randomElement($statusOptions);

             // Se o status for 'finalizado' ou 'indeferido', use um usuário real
             $finalizadoPor = null;
             $resolvedAt = null;
             $tempoResolucao = null;
             
             if ($status === 'finalizado' || $status === 'indeferido') {
                 // Pegar aleatoriamente um usuário da coleção
                 $randomUser = $users->random();
                 $finalizadoPor = $randomUser->name;
                 $resolvedAt = $faker->dateTimeBetween('-1 month', 'now');
                 $tempoResolucao = $faker->numberBetween(1, 120);
             }
            
            // Cria um objeto dadosExtra com valores aleatórios
            $dadosExtra = [
                'ano' => $faker->year,
                'semestre' => $faker->randomElement([1, 2]),
                'via' => $faker->randomElement(['1ª via', '2ª via']),
                'opcao_reintegracao' => $faker->randomElement(['Reintegração', 'Estágio', 'Entrega do Relatório de Estágio', 'TCC']),
                'componente_curricular' => $faker->sentence(3),
                'nome_professor' => $faker->name,
                'unidade' => $faker->randomElement(['1ª unidade', '2ª unidade', '3ª unidade', '4ª unidade', 'Exame Final']),
                'ano_semestre' => $faker->randomElement(['2024.1', '2024.2', '2025.1'])
            ];
        
            ApplicationRequest::create([
                'key' => Str::uuid(),
                'nomeCompleto' => $faker->name,
                'cpf' => $faker->cpf,
                'celular' => $faker->phoneNumber,
                'email' => $faker->firstName($faker->name) . '@discente.ifpe.edu.br',
                'rg' => $faker->rg,
                'orgaoExpedidor' => $faker->randomElement(['SSP', 'SDS', 'PM']),
                'campus' => $faker->randomElement($campusOptions),
                'matricula' => $faker->randomNumber(8, true),
                'situacao' => $faker->randomElement($situacaoOptions),
                'curso' => $faker->randomElement(['Informática para Internet', 'Logística', 'Gestão da Qualidade', 'Administração', 'Tecnologia em Sistemas para Internet']),
                'periodo' => $faker->numberBetween(1, 6),
                'turno' => $faker->randomElement(['Manhã', 'Tarde']),
                'tipoRequisicao' => $faker->randomElement($tipoRequisicaoOptions),
                'observacoes' => $faker->sentence,
                'resposta' => $faker->sentence,
                'status' => $faker->randomElement($statusOptions),
                'motivo' => $faker->optional(0.3)->sentence(),
                'dadosExtra' => json_encode($dadosExtra),
                'finalizado_por' => $finalizadoPor,
                'resolved_at' => $resolvedAt,
                'tempoResolucao' => $tempoResolucao
            ]);
        }
    }
}

// Não esquece de rodar: php artisan db:seed --class=ApplicationSeeder

?>
