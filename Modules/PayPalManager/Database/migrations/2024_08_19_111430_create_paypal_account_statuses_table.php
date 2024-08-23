<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('paypal_account_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Work, Pending, Limited 180d, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paypal_account_statuses');
    }
};
