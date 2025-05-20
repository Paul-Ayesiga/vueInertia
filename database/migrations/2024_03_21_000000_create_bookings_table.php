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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->foreignId('organiser_id')->constrained()->onDelete('cascade');
            $table->string('event_name');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue_name');
            $table->string('venue_location');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->decimal('price', 10, 2);
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
}; 