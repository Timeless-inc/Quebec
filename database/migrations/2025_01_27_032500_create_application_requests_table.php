<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('requerimentos', function (Blueprint $table) {
            $table->id(); // ID auto-incrementável
            $table->uuid('key')->unique(); // Chave única para identificar o requerimento
            $table->string('nomeCompleto'); // Nome completo do requerente
            $table->string('cpf')->unique(); // CPF do requerente
            $table->string('celular'); // Número de celular
            $table->string('email'); // Email do requerente
            $table->string('rg'); // RG do requerente
            $table->string('orgaoExpedidor'); // Órgão expedidor do RG
            $table->string('campus'); // Campus do aluno
            $table->string('matricula')->unique(); // Número de matrícula
            $table->string('situacao'); // Situação acadêmica (matriculado, graduado, etc.)
            $table->string('curso'); // Nome do curso
            $table->string('periodo'); // Período atual
            $table->string('turno'); // Turno do curso (manhã/tarde)
            $table->string('tipoRequisicao'); // Tipo de requisição acadêmica
            $table->string('anexarArquivos')->nullable(); // Nome do arquivo anexado (se houver)
            $table->text('observacoes')->nullable(); // Campo para observações adicionais
            $table->timestamps(); // Campos created_at e updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('requerimentos');
    }
};
