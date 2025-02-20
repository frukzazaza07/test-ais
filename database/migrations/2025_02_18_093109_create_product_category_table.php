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
        Schema::create('product_category', function (Blueprint $table) {
            $table->id();
            $table->string('pc_name_th')->unique();
            $table->string('pc_name_en')->unique();
            $table->string('pc_prefix_serial_number')->unique();
            $table->unsignedBigInteger('pc_user_id');
            $table->timestamps();
            $table->foreign('pc_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category');
    }
};
