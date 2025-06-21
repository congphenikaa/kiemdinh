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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique(); // Mã môn học
            $table->string('name'); // Tên môn học
            $table->integer('credit_hours'); // Số tín chỉ
            $table->integer('total_sessions'); // Tổng số tiết
            $table->text('description')->nullable();
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
