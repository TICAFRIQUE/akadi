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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // code de coupon
            $table->double('pourcentage_coupon')->nullable();
            $table->dateTime('date_debut_coupon')->nullable();
            $table->dateTime('date_fin_coupon')->nullable();
            $table->string('status_coupon')->nullable(); // en cour , terminer, bientot
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
