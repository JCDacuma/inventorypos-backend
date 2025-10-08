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
       
        //For supplier contact
        Schema::create('supplier_contacts', function(Blueprint $table){
             $table->id();
             $table->string('firstname');
             $table->string('lastname');
             $table->string('phonenumber', 20);
             $table->string('email');
             $table->timestamps();
        });

        //for supplier 
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('suppliername');
            $table->string('supplier_address');
            $table->integer('shipping_fee')->default(0);
            $table->boolean('vat_registered');
            $table->foreignId('supplier_contact_id')->nullable()->constrained('supplier_contacts')->onDelete('set null');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('supplier_contact');
    }
};
