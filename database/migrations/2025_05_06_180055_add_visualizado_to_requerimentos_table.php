<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('requerimentos', function (Blueprint $table) {
        $table->boolean('visualizado')->default(false);
    });
}

public function down()
{
    Schema::table('requerimentos', function (Blueprint $table) {
        $table->dropColumn('visualizado');
    });
}
};
