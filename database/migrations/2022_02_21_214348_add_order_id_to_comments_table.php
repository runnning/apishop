<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('comments', static function (Blueprint $table) {
            $table->integer('order_id')->after('user_id')->comment('评论的商品,所属的订单');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('comments', static function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
}
