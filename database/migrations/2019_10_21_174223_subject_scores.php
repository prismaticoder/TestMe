<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubjectScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $subjects = ['mathematics','english','rnv','physics','basic_science','basic_tech','biology','chemistry','yoruba','ict','cca','french','phonics','music'];
        //Create the J1 Physics table
            foreach ($subjects as $subject) {
                Schema::create($subject.'_scores', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('class_id')->unsigned();
                    $table->foreign('class_id')->references('id')->on('classes');
                    $table->unsignedBigInteger('student_id');
                    $table->foreign('student_id')->references('id')->on('students');
                    $table->integer('score')->default(0);
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
