<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('slot_id');
            $table->string('jitsi_url');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('meeting_name')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Request Sent', 'Confirmed', 'Completed', 'Canceled'])->default('Request Sent');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('slot_id')->references('id')->on('available_slots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
