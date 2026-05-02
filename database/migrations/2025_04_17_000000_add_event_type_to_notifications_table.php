<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventTypeToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('event_type')->nullable()->after('message')->comment('Tipo de evento: request_created, status_changed, event_created, event_expiring, user_registered');
            $table->unsignedBigInteger('related_id')->nullable()->after('event_type')->comment('ID do requerimento, evento ou outra entidade relacionada');
            $table->index(['user_id', 'is_read']);
            $table->index(['event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_read']);
            $table->dropIndex(['event_type']);
            $table->dropColumn(['event_type', 'related_id']);
        });
    }
}
