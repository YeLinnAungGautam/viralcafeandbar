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
        Schema::create('tag_line_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_line_id');
            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_line_id')->references('id')->on('tag_lines')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_line_tags');
    }
};
