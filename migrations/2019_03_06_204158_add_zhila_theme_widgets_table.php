<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZhilaThemeWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_widgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app',255);
            $table->string('theme',255);
            $table->string('widget_area',255);
            $table->string('widget_slug',255);
            $table->string('widget_ctl',255);
            $table->string('widget_id',255);
            $table->string('content',255);
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
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
