<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestForwardings extends Migration
{
    public function up()
    {
        Schema::create('request_forwardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requerimento_id')->constrained('requerimentos');
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->enum('status', ['encaminhado', 'finalizado', 'indeferido', 'pendente', 'devolvido'])->default('encaminhado');
            $table->text('internal_message')->nullable();
            $table->boolean('is_returned')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_forwardings');
    }
}