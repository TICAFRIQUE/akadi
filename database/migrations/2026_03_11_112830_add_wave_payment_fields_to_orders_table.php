<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('wave_session_id')->nullable()->after('payment_method_id');
            $table->string('wave_payment_id')->nullable()->after('wave_session_id');
            $table->string('payment_status')->default('pending')->after('wave_payment_id')
                ->comment('pending, completed, failed, cancelled');
            $table->timestamp('payment_completed_at')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'wave_session_id',
                'wave_payment_id',
                'payment_status',
                'payment_completed_at',
            ]);
        });
    }
};
