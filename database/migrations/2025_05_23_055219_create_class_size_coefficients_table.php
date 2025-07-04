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
        Schema::create('class_size_coefficients', function (Blueprint $table) {
        $table->id();
        $table->integer('min_students');
        $table->integer('max_students')->nullable();
        $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
        $table->decimal('coefficient', 3, 2);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_size_coefficients');
    }
};
