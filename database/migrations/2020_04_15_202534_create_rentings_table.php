<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('openid', 50)->default('')->comment('小程序ID号');
            $table->string('nickname', 50)->default('')->comment('昵称');
            $table->string('phone', 15)->default('')->comment('手机');
            $table->enum('sex', ['男', '女'])->default('男')->comment('性别');
            $table->unsignedTinyInteger('age')->default(0)->comment('年龄');
            $table->string('avatar', 200)->default('')->comment('头像');
            $table->string('card', 18)->default('')->comment('身份证号');
            $table->string('card_img', 255)->default('')->comment('身份证照片');
            $table->enum('is_auth', ['0', '1'])->default('0')->comment('认证未认证0否，1是');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentings');
    }
}
