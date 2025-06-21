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
            $table->string('status')->default('pending');
            $table->foreignId('semester_id')->constrained();
            $table->integer('total_sessions'); // Tổng số tiết đã dạy
            $table->decimal('degree_coefficient', 3, 2);
            $table->decimal('size_coefficient', 3, 2);
            $table->decimal('base_rate', 10, 2);
            $table->decimal('total_amount', 12, 2);
            $table->date('payment_date')->nullable();
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
