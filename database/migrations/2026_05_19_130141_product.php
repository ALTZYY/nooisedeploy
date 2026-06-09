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
        Schema::create('products', function (Blueprint $table) {
            // Product ID (Primary Key): UUID unik
            $table->uuid('id')->primary();

            // SKU: kode unik internal untuk inventaris
            $table->string('sku')->unique();

            // Nama Produk: nama lengkap yang akan muncul di etalase
            $table->string('name');

            // Brand/Merk: produsen produk
            $table->string('brand');

            // Deskripsi produk
            $table->longText('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

