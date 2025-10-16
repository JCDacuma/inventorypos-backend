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

         Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->string('unitname');
            $table->string('symbol');
            $table->string('description');
             $table->string('unit_status');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('product_image');
            $table->string('productname');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('product_units')->onDelete('cascade');
            $table->decimal('markup_price', 10, 2)->default(0); 
            $table->decimal('raw_price', 10, 2)->default(0); 
            $table->decimal('selling_price', 10, 2)->default(0); 
            $table->boolean('taxable')->default(false);
            $table->string('product_status');
            $table->integer('reorder_level');
            $table->text('description');
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('products');
    }
};
