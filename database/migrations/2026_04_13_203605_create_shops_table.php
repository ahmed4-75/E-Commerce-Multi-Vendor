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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('phone')->nullable()->unique();
            $table->string('email')->unique();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->string('pincode')->unique();
            $table->string('website')->nullable()->unique();
            $table->string('bank_name');
            $table->string('bank_code');
            $table->string('bank_country');
            $table->string('bank_address');
            $table->string('account_name');
            $table->string('account_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
