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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('price')->default(0);
            $table->string('image')->nullable();
            $table->json('tracklist')->nullable();
            $table->string('color')->nullable();
            $table->string('vinyl_class')->nullable();
            $table->string('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price', 'image', 'tracklist', 'color', 'vinyl_class', 'category']);
        });
    }
};
