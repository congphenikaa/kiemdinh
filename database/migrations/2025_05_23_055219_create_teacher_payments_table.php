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
        Schema::create('teacher_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained();
            $table->foreignId('class_id')->constrained();
            $table->foreignId('payment_batch_id')->nullable()->constrained();
            $table->string('status')->default('pending'); // pending, paid, cancelled
            $table->foreignId('semester_id')->constrained();
            $table->integer('total_sessions'); // Tổng số tiết đã dạy
            $table->integer('theory_sessions')->default(0); // Số tiết lý thuyết
            $table->integer('practice_sessions')->default(0); // Số tiết thực hành
            $table->decimal('degree_coefficient', 3, 2); // Hệ số bằng cấp
            $table->decimal('size_coefficient', 3, 2); // Hệ số sĩ số
            $table->decimal('base_rate', 10, 2); // Lương cơ bản/tiết
            $table->decimal('total_amount', 12, 2); // Tổng lương
            $table->date('payment_date')->nullable(); // Ngày thanh toán
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_payments');
    }
};
