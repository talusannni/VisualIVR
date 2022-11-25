<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('sheets', function(Blueprint $table)
      {
        $table->increments('id');
        $table->string('sheet_name');
        $table->text('path')->nullable();
        $table->integer('project_id')->unsigned();
        $table->index('id');
        $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
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
      Schema::drop('sheets');
    }
}
