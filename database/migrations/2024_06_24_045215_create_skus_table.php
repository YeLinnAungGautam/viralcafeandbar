<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('stock')->default(99);
            $table->enum('stock_status', ['in_stock', 'out_stock', 'on_backorder'])->default('in_stock');
            $table->integer('weight')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->enum('discount_type', ['percent', 'fix'])->nullable();
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('discount_schedule')->default(false);
            $table->datetime('discount_start_date')->nullable();
            $table->datetime('discount_end_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
