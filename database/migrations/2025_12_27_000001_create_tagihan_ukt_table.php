<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_ukt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->string('tahun_akademik')->nullable();
            $table->string('semester')->nullable();
            $table->unsignedBigInteger('nominal')->default(3500000);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('order_id')->unique();
            $table->string('payment_link')->nullable();
            $table->string('mayar_transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['mahasiswa_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_ukt');
    }
};
