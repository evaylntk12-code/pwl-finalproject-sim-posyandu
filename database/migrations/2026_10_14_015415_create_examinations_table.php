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
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toddler_id')->constrained()->cascadeOnDelete();
            $table->date('examination_date');
            $table->decimal('weight', 5, 2);             // kg
            $table->decimal('height', 5, 1);             // cm
            $table->decimal('head_circumference', 4, 1); // cm
            $table->decimal('arm_circumference', 4, 1);  // cm
            $table->string('nutrition_status', 20);      // Gizi Baik / Gizi Kurang / Gizi Lebih
            $table->text('notes')->nullable();
            $table->timestamps();

            // Satu balita hanya boleh punya satu pemeriksaan per hari
            $table->unique(['toddler_id', 'examination_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
