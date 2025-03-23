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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // Unique ticket number
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade'); // Relates to the meetings table
            $table->decimal('price', 10, 2)->default(0); // Ticket price
            $table->enum('status', ['reserved', 'paid', 'cancelled'])->default('reserved'); // Status of the ticket
            $table->foreignId('attendee_id')->nullable()->constrained('users')->onDelete('set null'); // Optional: Attendee, assuming you have a 'users' table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
