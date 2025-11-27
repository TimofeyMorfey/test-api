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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('g_number');
            $table->date('date');
            $table->date('last_change_date');
            $table->string('supplier_article');
            $table->string('tech_size');
            $table->unsignedBigInteger('barcode');
            $table->decimal('total_price', 20, 12);
            $table->integer('discount_percent');
            $table->boolean('is_supply');
            $table->boolean('is_realization');
            $table->string('promo_code_discount')->nullable();
            $table->string('warehouse_name');
            $table->string('country_name');
            $table->string('oblast_okrug_name');
            $table->string('region_name');
            $table->integer('income_id');
            $table->string('sale_id');
            $table->integer('odid')->nullable();
            $table->integer('spp');
            $table->decimal('for_pay', 10, 2);
            $table->unsignedBigInteger('finished_price');
            $table->unsignedBigInteger('price_with_disc');
            $table->unsignedBigInteger('nm_id');
            $table->string('subject');
            $table->string('category');
            $table->string('brand');
            $table->boolean('is_storno');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
