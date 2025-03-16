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
        Schema::create('sku_attribute_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sku_id')->index();
            $table->foreign('sku_id')->references('id')->on('skus')->cascadeOnDelete();
            $table->unsignedBigInteger('attribute_option_id')->index();
            $table->foreign('attribute_option_id')->references('id')->on('attribute_options')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_attribute_options');
    }
};
