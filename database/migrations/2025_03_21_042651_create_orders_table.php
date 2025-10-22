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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('order_number')->unique();
            $table->string('address')->nullable();
            $table->float('quantity')->nullable();
            $table->float('total_amount')->nullable();
            $table->enum('status', ['diterima', 'diproses', 'selesai', 'lunas', 'belum lunas'])->default('diterima');
            $table->dateTime('pickup_date');
            $table->string('invoice_url')->nullable();
            $table->dateTime('estimated_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
