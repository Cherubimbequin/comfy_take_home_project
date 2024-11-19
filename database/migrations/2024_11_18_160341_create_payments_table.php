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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id');
            $table->foreignId('user_id');
            $table->string('reference')->unique();
            $table->string('channel')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('mobile_money_number')->nullable();
            $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payer_ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
