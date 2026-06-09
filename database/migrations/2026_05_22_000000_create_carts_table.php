<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // relasi user
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // karena produk pakai UUID (sesuai migration produk kamu), maka product_id juga UUID
            $table->uuid('product_id');
            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();

            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

