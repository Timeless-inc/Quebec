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
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->text('resposta')->nullable();
            $table->longText('anexos_finalizacao')->nullable();
        });
    }

    public function down()
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->dropColumn(['resposta', 'anexos_finalizacao']);
        });
    }
};
