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
            $table->string('p_serial_number', 20)->unique();
            $table->text('p_serial_number_image', 500)->nullable()->unique();
            $table->double('p_price');
            $table->unsignedBigInteger('p_pc_id');
            $table->unsignedBigInteger('p_created_by');
            $table->unsignedBigInteger('p_updated_by');
            $table->foreign('p_pc_id')->references('id')->on('product_category');
            $table->foreign('p_created_by')->references('id')->on('users');
            $table->foreign('p_updated_by')->references('id')->on('users');
            $table->timestamp('p_created_at')->nullable();
            $table->timestamp('p_updated_at')->nullable();
            $table->softDeletes('p_deleted_at');
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
