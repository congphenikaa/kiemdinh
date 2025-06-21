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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->date('dob');
            $table->string('gender')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->foreignId('degree_id')->constrained()->onDelete('cascade');
            $table->date('start_date')->nullable(); // Ngày bắt đầu làm việc
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
