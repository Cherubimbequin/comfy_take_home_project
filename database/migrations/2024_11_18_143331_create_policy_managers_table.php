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
        Schema::create('policy_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('policy_number')->unique();
            $table->foreignId('policy_type_id');
            $table->enum('status', ['Pending', 'Active', 'Expired'])->default('Pending');
            $table->decimal('premium_amount', 10, 2);
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('next_of_kin');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_managers');
    }
};
