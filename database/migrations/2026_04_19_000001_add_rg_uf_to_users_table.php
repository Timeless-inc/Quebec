<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('rg_uf', 2)->nullable()->after('rg');
            $table->dropUnique('users_rg_unique');
            $table->unique(['rg', 'rg_uf']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_rg_rg_uf_unique');
            $table->dropColumn('rg_uf');
            $table->unique('rg');
        });
    }
};