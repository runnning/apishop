<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('addresses', static function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('用户id');
            $table->string('name')->comment('收货人');
            $table->integer('city_id')->comment('地址city表中的id');
            $table->string('address')->comment('详细地址');
            $table->string('phone')->comment('手机号');
            $table->tinyInteger('is_default')->default(0)->comment('是否是默认地址: 0不是默认 1默认');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
}
