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
            $table->string('pc_name_th');
            $table->string('pc_name_en');
            $table->string('pc_prefix_serial_number');
            $table->unsignedBigInteger('pc_created_by');
            $table->unsignedBigInteger('pc_updated_by');
            $table->timestamp('pc_created_at')->nullable();
            $table->timestamp('pc_updated_at')->nullable();
            $table->softDeletes('pc_deleted_at');
            $table->foreign('pc_created_by')->references('id')->on('users');
            $table->foreign('pc_updated_by')->references('id')->on('users');
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
