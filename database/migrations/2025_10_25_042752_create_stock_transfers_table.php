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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_code')->unique();
            $table->unsignedBigInteger('transfer_from');
            $table->unsignedBigInteger('transfer_to');
            $table->integer('quantity_moved');
            $table->dateTime('movement_date');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('moved_by');
            $table->timestamps();

            $table->foreign('transfer_from')->references('id')->on('product_stocks')->onDelete('cascade');
            $table->foreign('transfer_to')->references('id')->on('product_stocks')->onDelete('cascade');

        });

        Schema::create('stock_movements', function(Blueprint $table){
            $table->id();
            $table->string('movement_code')->unique();
            $table->unsignedBigInteger('stock_moved');
            $table->integer('quantity_moved');
            $table->string('movement_type');
            $table->string('action_type');
            $table->dateTime('movement_date');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('moved_by');
            $table->timestamps();

            $table->foreign('stock_moved')->references('id')->on('product_stocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('stock_movements');
    }
};
