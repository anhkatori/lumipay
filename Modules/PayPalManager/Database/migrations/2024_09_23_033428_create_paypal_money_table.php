<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('paypal_money', function (Blueprint $table) {
            $table->id();
            $table->string('account_id');
            $table->string('paypal_email');
            $table->decimal('money', 15, 2)->default(0);
            $table->string('buyer_email');
            $table->string('buyer_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paypal_money');
    }
};
