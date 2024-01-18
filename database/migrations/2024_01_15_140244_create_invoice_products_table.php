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
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->string("qty");
            $table->string("sale_price");

            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("invoice_id");
            $table->unsignedBigInteger("product_id");

            $table->foreign("user_id")->references("id")->on("users")->restrictOnDelete();
            $table->foreign("invoice_id")->references("id")->on("invoices")->restrictOnDelete();
            $table->foreign("product_id")->references("id")->on("products")->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_products');
    }
};
