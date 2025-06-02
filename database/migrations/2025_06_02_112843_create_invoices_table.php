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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('milestone_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('payment_method', ['bank_transfer', 'online'])->nullable();
            $table->string('payment_proof')->nullable();
            $table->date('due_date');
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('reference')->nullable()->unique();
            $table->string('order_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
