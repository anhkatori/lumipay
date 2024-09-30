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
        Schema::create('stripe_money', function (Blueprint $table) {
            $table->id();
            $table->string('account_id');
            $table->string('stripe_domain');
            $table->smallInteger('status');
            $table->decimal('money', 15, 2)->default(0);
            $table->string('buyer_email');
            $table->string('buyer_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stripe_money');
    }
};
