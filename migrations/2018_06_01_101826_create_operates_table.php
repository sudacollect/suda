<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('salt',64);
            $table->string('avatar')->nullable();
            $table->boolean('is_company')->index();
            $table->string('company')->unique()->nullable();
            $table->string('province')->index()->nullable();
            $table->string('city')->index()->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('superadmin')->default(0);  //是否是超级管理员
            $table->string('roletable')->nullable();
            $table->integer('role_id')->nullable();
            $table->bigInteger('organization_id');
            $table->tinyInteger('enable')->default(1);
            $table->string('permission')->nullable();//权限集合
            $table->rememberToken();
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
        //Schema::dropIfExists('operates');
    }
}
