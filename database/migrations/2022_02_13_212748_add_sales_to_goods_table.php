<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalesToGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('goods', static function (Blueprint $table) {
            $table->integer('sales')->default(0)->after('stock')->comment('销量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('goods', static function (Blueprint $table) {
            $table->dropColumn('sales');
        });
    }
}
