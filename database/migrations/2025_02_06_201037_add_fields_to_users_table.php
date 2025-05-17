<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::table('users', function (Blueprint $table) {
        $table->string('matricula')->unique()->nullable();
        $table->string('second_matricula')->nullable();
        $table->string('rg')->unique()->nullable();
        $table->string('cpf')->unique()->nullable();
    });
    }

    public function down()
    {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['matricula','second_matricula', 'rg', 'cpf']);
    });
    }

};
