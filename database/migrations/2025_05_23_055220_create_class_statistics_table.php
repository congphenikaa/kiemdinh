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
        Schema::create('class_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained();
            $table->integer('total_sessions_taught')->default(0);
            $table->integer('total_sessions_cancelled')->default(0);
            $table->decimal('average_attendance', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_statistics');
    }
};
