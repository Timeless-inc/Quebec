<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->enum('status', ['em_andamento', 'finalizado', 'indeferido', 'pendente'])->default('em_andamento');
        });
    }

    public function down()
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
