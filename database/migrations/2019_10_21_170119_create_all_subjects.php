<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subjects = ['mathematics','english','rnv','basic_science','basic_tech','biology','chemistry','yoruba','ict','cca','french','phonics','music'];
        //Create the J1 Physics table
        for ($i=1; $i < 4; $i++) {
            foreach ($subjects as $subject) {
                Schema::create('jss'.$i.'_' .$subject, function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('class_id')->unsigned();
                    $table->foreign('class_id')->references('id')->on('classes');
                    $table->longText('question');
                    $table->json('options');
                    $table->string('answer');
                });
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_subjects');
    }
}
