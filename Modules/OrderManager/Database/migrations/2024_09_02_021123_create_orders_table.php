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
            $table->string('request_id', 191)->nullable();
            $table->string('amount', 191)->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('email', 191)->nullable();
            $table->string('ip', 191)->nullable();
            $table->string('description', 191)->nullable();
            $table->string('cancel_url', 191)->nullable();
            $table->string('return_url', 191)->nullable();
            $table->string('notify_url', 191)->nullable();
            $table->string('method', length: 191)->nullable();
            $table->string('status', 191)->nullable();
            $table->string('canceled', 1)->nullable();
            $table->unsignedBigInteger('method_account')->nullable();
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
