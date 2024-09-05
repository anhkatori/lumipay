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
            $table->string('increment_id', 191)->index(); // Tạo cột index
            $table->string('status', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('name', 191)->nullable();
            $table->integer('total_item_count')->nullable();
            $table->integer('total_qty_ordered')->nullable();
            $table->decimal('total', 12, 4)->default(0.0000);
            $table->decimal('shipping_invoiced', 12, 4)->default(0.0000);
            $table->decimal('base_shipping_invoiced', 12, 4)->default(0.0000);
            $table->decimal('shipping_refunded', 12, 4)->default(0.0000);
            $table->decimal('base_shipping_refunded', 12, 4)->default(0.0000);
            $table->unsignedInteger('customer_id')->nullable()->index();
            $table->string('customer_type', 191)->nullable();
            $table->integer('cart_id')->nullable();
            $table->string('applied_cart_rule_ids', 191)->nullable();
            $table->decimal('shipping_discount_amount', 12, 4)->default(0.0000);
            $table->decimal('base_shipping_discount_amount', 12, 4)->default(0.0000);
            $table->integer('class_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order');
    }
};
