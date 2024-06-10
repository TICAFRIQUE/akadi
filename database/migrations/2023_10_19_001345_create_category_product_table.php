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
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
            ->nullable()
            ->constrained('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('product_id')
            ->nullable()
            ->constrained('products')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            // $table->foreignIdFor(\App\Models\Category::class)->constrained()->onDelete('cascade');
            // $table->foreignIdFor(\App\Models\Product::class)->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_product');
    }
};
