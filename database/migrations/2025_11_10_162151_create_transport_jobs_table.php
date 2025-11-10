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
        Schema::create('transport_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_address');
            $table->string('delivery_address');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('status')->default('assigned');
            $table->foreignId('carrier_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'carrier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_jobs');
    }
};

