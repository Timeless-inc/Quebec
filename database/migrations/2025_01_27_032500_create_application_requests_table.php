<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('requerimentos', function (Blueprint $table) {
            $table->id();
            $table->uuid('key')->unique();
            $table->string('nomeCompleto');
            $table->string('cpf');
            $table->string('celular');
            $table->string('email');
            $table->string('rg');
            $table->string('orgaoExpedidor');
            $table->string('campus');
            $table->string('matricula');
            $table->string('situacao');
            $table->string('curso');
            $table->string('periodo');
            $table->string('turno');
            $table->string('tipoRequisicao');
            $table->longText('anexarArquivos')->nullable();
            $table->text('observacoes')->nullable();
            $table->json('dadosExtra')->nullable(); // Adicionado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requerimentos');
    }
};