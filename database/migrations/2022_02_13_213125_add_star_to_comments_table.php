<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStarToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('comments', static function (Blueprint $table) {
            $table->tinyInteger('star')->default(5)->after('rate')->comment('0-5星');
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
            $table->dropColumn('star');
        });
    }
}
