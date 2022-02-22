<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_details', static function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->comment('所属订单');
            $table->integer('goods_id')->comment('商品');
            $table->integer('price')->comment('商品的价格');
            $table->integer('num')->comment('商品数量');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
}
