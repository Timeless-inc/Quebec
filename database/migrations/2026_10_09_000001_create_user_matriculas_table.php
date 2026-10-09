<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('matricula')->unique();
            $table->timestamps();
        });

        // A antiga segunda matrícula passa a ser a primeira matrícula adicional
        $segundasMatriculas = DB::table('users')
            ->whereNotNull('second_matricula')
            ->where('second_matricula', '!=', '')
            ->get(['id', 'second_matricula']);

        foreach ($segundasMatriculas as $usuario) {
            DB::table('user_matriculas')->insertOrIgnore([
                'user_id' => $usuario->id,
                'matricula' => $usuario->second_matricula,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('profile_change_requests')
            ->where('field', 'second_matricula')
            ->update(['field' => 'matricula_adicional']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('second_matricula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('second_matricula')->nullable()->after('matricula');
        });

        // Só a primeira matrícula adicional cabe na coluna antiga
        $matriculas = DB::table('user_matriculas')->orderBy('id')->get(['user_id', 'matricula']);

        foreach ($matriculas->unique('user_id') as $matricula) {
            DB::table('users')
                ->where('id', $matricula->user_id)
                ->update(['second_matricula' => $matricula->matricula]);
        }

        DB::table('profile_change_requests')
            ->where('field', 'matricula_adicional')
            ->update(['field' => 'second_matricula']);

        Schema::dropIfExists('user_matriculas');
    }
};
