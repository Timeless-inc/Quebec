<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLastRequisitionDataTable extends Migration
{
    public function up()
    {
        Schema::create('user_last_requisition_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome_completo')->nullable();
            $table->string('cpf')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->nullable();
            $table->string('rg')->nullable();
            $table->string('orgao_expedidor')->nullable();
            $table->string('matricula')->nullable();
            $table->string('campus')->nullable();
            $table->unsignedTinyInteger('curso')->nullable();
            $table->unsignedTinyInteger('situacao')->nullable();
            $table->unsignedTinyInteger('periodo')->nullable();
            $table->string('turno')->nullable();
            $table->string('tipo_requisicao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_last_requisition_data');
    }
}