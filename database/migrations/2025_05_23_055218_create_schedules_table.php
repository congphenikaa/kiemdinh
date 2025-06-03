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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes');
            $table->integer('day_of_week'); // 1-7 (Thứ 2-Chủ nhật)
            $table->time('start_time');
            $table->time('end_time');
            $table->date('date')->nullable(); // Cho lịch linh hoạt
            $table->string('session_type')->default('theory'); // theory, practice
            $table->boolean('is_cancelled')->default(false);
            $table->text('cancellation_reason')->nullable();
            $table->integer('session_number');
            $table->boolean('is_taught')->default(false); // Đã dạy?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
