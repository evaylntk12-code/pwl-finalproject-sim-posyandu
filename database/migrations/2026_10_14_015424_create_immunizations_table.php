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
        Schema::create('immunizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toddler_id')->constrained()->cascadeOnDelete();
            $table->string('vaccine_type', 50);
            $table->date('immunization_date');
            $table->date('next_date')->nullable();
            $table->string('status', 15)->default('Terjadwal'); // Terjadwal / Selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immunizations');
    }
};
