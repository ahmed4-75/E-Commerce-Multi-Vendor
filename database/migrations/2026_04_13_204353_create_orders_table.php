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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->text('address');
            $table->string('phone')->nullable();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->decimal('amount',8,2);
            $table->string('currency')->default('EGP');
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->integer('gateway_order_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
