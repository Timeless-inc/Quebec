<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_forwardings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('request_forwardings', function (Blueprint $table) {
            $table->enum('status', ['encaminhado', 'finalizado', 'indeferido', 'pendente', 'devolvido', 'reencaminhado'])->default('encaminhado');
        });
    }

    public function down(): void
    {
        Schema::table('request_forwardings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('request_forwardings', function (Blueprint $table) {
            $table->enum('status', ['encaminhado', 'finalizado', 'indeferido', 'pendente', 'devolvido'])->default('encaminhado');
        });
    }
};
