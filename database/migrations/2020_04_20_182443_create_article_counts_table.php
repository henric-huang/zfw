<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_counts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('openid', 50)->unique()->default('')->comment('openid');
            $table->unsignedInteger('art_id')->default(0)->comment('文章ID');
            $table->date('vdt')->nullable()->comment('日期');
            $table->unsignedInteger('vtime')->default(0)->comment('时间');
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
        Schema::dropIfExists('article_counts');
    }
}
