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
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_ban');
            $table->string('sort_ip');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blocked_ips');
    }
};
