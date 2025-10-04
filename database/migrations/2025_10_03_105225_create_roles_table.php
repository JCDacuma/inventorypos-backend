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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string("role_name");
            $table->boolean("can_edit_price")->default(false);
            $table->boolean("can_edit_item_info")->default(false);
            $table->boolean("can_edit_stocks")->default(false);
            $table->boolean("can_order_supplies")->default(false);
            $table->boolean("can_delete")->default(false);
            $table->boolean("is_admin")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
