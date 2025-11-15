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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->string('midtrans_transaction_id');
            $table->string('payment_type');
            $table->string('transaction_status');
            $table->unsignedInteger('gross_amount');
            $table->text('raw_response');
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transaction')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
