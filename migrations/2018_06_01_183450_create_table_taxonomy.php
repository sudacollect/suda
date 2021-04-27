<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTaxonomy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('taxonomy')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('taxonomies', function(Blueprint $table)
        {
             $table->increments('id');

            $table->integer('term_id')
                  ->nullable()
                  ->unsigned()
                  ->references('id')
                  ->on('terms')
                  ->onDelete('cascade');

            $table->string('taxonomy')->default('default');
            $table->string('desc')->nullable();

            $table->integer('parent')->unsigned()->default(0);

            $table->smallInteger('sort')->unsigned()->default(0);

            $table->timestamps();
            $table->softDeletes();

             $table->unique(['term_id', 'taxonomy']);
        });

        Schema::create('taxables', function(Blueprint $table)
        {
            $table->integer('taxonomy_id')
                  ->nullable()
                  ->unsigned()
                  ->references('id')
                  ->on('taxonomies');

            $table->nullableMorphs('taxable');
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
