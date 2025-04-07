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
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->string('note_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('restrict');
            $table->date('delivery_date');
            $table->string('delivery_address');
            $table->string('receiver_name');
            $table->string('receiver_dni')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('delivered_by')->constrained('employees')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};
