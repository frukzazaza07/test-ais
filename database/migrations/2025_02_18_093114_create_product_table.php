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
            $table->string('p_name_th');
            $table->string('p_name_en');
            $table->string('p_serial_number')->unique();
            $table->double('p_price');
            $table->unsignedBigInteger('p_pc_id');
            $table->unsignedBigInteger('p_created_by');
            $table->unsignedBigInteger('p_updated_by');
            $table->foreign('p_pc_id')->references('id')->on('product_category');
            $table->foreign('p_created_by')->references('id')->on('users');
            $table->foreign('p_updated_by')->references('id')->on('users');
            $table->timestampTz('p_date_of_admission')->useCurrent();
            $table->timestamps();
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
