<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('base_score');
            $table->date('date');
            $table->integer('hours');
            $table->integer('minutes');
            $table->integer('unique_code');
            $table->boolean('has_started')->default(0);

            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('classes');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('duplicated_from')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('teachers')->onDelete('SET NULL');

            $table->timestamp('started_at')->nullable();
            $table->timestamps();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->foreign('duplicated_from')->references('id')->on('exams')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
