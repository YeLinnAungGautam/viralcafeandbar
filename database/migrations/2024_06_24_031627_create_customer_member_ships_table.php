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
        Schema::create('customer_member_ships', function (Blueprint $table) {
            $table->id();
            $table->decimal('point_history', 10, 2)->default(0);
            $table->decimal('point', 10, 2)->default(0);
            $table->integer('percentage')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('member_ship_id')->nullable();
            $table->foreign('member_ship_id')->references('id')->on('member_ships');
            $table->enum('is_percentage_default', [1, 0])->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_member_ships');
    }
};
