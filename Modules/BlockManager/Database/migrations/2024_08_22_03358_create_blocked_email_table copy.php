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
        Schema::create('blocked_emails', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('name');
            $table->decimal('money_account', 30, 2)->default(0);
            $table->decimal('money_bonus', 30, 2)->default(0);
            $table->string('status_lock')->nullable();
            $table->string('status_delete')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blocked_emails');
    }
};
