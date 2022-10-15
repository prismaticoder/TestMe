<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteCascadeToTeacherSubjectClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachersubject_class', function (Blueprint $table) {
            $table->dropForeign('adminsubject_class_adminsubject_id_foreign');
            $table->foreign('teachersubject_id')->references('id')->on('teacher_subject')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_subject_class', function (Blueprint $table) {
            $table->dropForeign(['adminsubject_id']);
            $table->foreign('adminsubject_id')->references('id')->on('teacher_subject');
        });
    }
}
