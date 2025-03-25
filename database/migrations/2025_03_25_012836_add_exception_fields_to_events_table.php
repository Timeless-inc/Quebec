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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_exception')->default(false);
            $table->unsignedBigInteger('exception_user_id')->nullable();
            $table->foreign('exception_user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['exception_user_id']);
            $table->dropColumn(['is_exception', 'exception_user_id']);
        });
    }
};
