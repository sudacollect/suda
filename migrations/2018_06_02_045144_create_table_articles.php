<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255);
            $table->integer('hero_image')->nullable();//关联media_id
            $table->integer('operate_id');
            $table->integer('display_operate_id');
            $table->text('content');
            $table->integer('parent_id')->nullable();
            $table->integer('sort')->default(0);
            $table->string('template_name',255)->nullable();
            $table->string('password',64)->nullable();
            $table->tinyInteger('need_login')->default(0);
            $table->tinyInteger('disable')->default(0);
            $table->text('permission')->nullable();//权限集合
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
        //
    }
}
