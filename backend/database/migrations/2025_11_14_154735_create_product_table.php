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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('brand_id');
            $table->string('product_name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('stock');
            $table->decimal('rating', 1, 2)->nullable();
            $table->text('spesification_product');
            $table->text('information_product');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('type_id')->references('id')->on('type_product')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('brand_id')->references('id')->on('brand_product')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
