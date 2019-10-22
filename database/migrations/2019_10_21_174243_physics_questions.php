<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhysicsQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($i=2; $i < 4; $i++) {
            Schema::create('jss'.$i.'_physics', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('class_id')->unsigned();
                $table->foreign('class_id')->references('id')->on('classes');
                $table->longText('question');
                $table->json('options');
                $table->string('answer');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
