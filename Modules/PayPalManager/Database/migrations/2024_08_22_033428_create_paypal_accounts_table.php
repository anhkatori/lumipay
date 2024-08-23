<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('paypal_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('password');
            $table->string('domain_site_fake');
            $table->decimal('max_receive_amount', 15, 2)->default(0);
            $table->decimal('active_amount', 15, 2)->default(0);
            $table->decimal('hold_amount', 15, 2)->default(0);
            $table->decimal('max_order_receive_amount', 15, 2)->default(0);
            $table->string('proxy')->nullable();
            $table->integer('days_stopped')->default(0);
            $table->text('description')->nullable();
            $table->string('payment_method');
            $table->foreignId('status_id')->constrained('paypal_account_statuses');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paypal_accounts');
    }
};
