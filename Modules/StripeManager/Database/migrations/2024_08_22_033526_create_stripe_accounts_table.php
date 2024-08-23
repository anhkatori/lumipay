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
        Schema::create('stripe_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->decimal('max_receive_amount', 15, 2)->default(0);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->decimal('max_order_receive_amount', 15, 2)->default(0);
            $table->smallInteger('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stripe_accounts');
    }
};
