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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->unsignedBigInteger('sku_attribute_option_id')->index()->nullable();
            $table->foreign('sku_attribute_option_id')->references('id')->on('sku_attribute_options');
            $table->unsignedBigInteger('attribute_id')->index()->nullable();
            $table->foreign('attribute_id')->references('id')->on('attributes');
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
