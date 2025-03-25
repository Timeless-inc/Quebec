<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            // Verificar se as colunas não existem antes de adicioná-las
            if (!Schema::hasColumn('requerimentos', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable();
            }
            
            if (!Schema::hasColumn('requerimentos', 'tempoResolucao')) {
                $table->integer('tempoResolucao')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            $table->dropColumn(['resolved_at', 'tempoResolucao']);
        });
    }
};