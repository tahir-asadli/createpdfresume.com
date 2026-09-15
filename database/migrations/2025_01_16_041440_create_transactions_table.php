<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('api_endpoint')->nullable();
            $table->string('order_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('card_id')->nullable();
            $table->string('subscription_id')->nullable();
            $table->string('status')->nullable();
            $table->string('code')->nullable();
            $table->string('message')->nullable();
            $table->string('transaction')->nullable();
            $table->string('bank_transaction')->nullable();
            $table->text('bank_response')->nullable();
            $table->string('card_name')->nullable();
            $table->string('card_mask')->nullable();
            $table->string('operation_code')->nullable();
            $table->string('rrn')->nullable();
            $table->string('amount')->nullable();
            $table->json('other_attr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
