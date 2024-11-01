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
        Schema::table('paypal_accounts', function (Blueprint $table) {
            $table->string('client_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('webhook_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paypal_accounts', function (Blueprint $table) {
            $table->dropColumn('client_key');
            $table->dropColumn('secret_key');
        });
    }
};
