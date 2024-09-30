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
        Schema::create('airwallet_money', function (Blueprint $table) {
            $table->id();
            $table->string('account_id');
            $table->string('domain');
            $table->decimal('money', 15, 2)->default(0);
            $table->string('buyer_email');
            $table->string('buyer_name');
            $table->smallInteger('status')->default(value: 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airwallet_money');
    }
};
