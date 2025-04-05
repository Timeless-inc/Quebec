<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('requisition_type_events', function (Blueprint $table) {
            $table->id();
            $table->integer('requisition_type_id');
            $table->boolean('requires_event')->default(true);
            $table->timestamps();
            
            $table->unique('requisition_type_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requisition_type_events');
    }
};