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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained(); // Relación con usuario del sistema
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dni')->unique(); // Documento de identidad
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('position'); // Cargo
            $table->date('hire_date');
            $table->boolean('can_manage_inventory')->default(false); // Permisos
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
