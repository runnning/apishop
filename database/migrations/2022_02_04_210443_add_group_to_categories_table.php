<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('categories', static function (Blueprint $table) {
            $table->string('group')->default('goods')->comment('分组: goods 商品分类 menu 菜单');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('categories', static function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
}
