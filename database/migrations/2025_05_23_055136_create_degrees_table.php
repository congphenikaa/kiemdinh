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
        Schema::create('degrees', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // VD: "Tiến sĩ", "Thạc sĩ"
            $table->string('short_name', 10); // VD: "TS", "ThS"
            $table->decimal('salary_coefficient', 3, 2)->default(1.00); // Hệ số lương
            $table->timestamps();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degrees');
    }
};
