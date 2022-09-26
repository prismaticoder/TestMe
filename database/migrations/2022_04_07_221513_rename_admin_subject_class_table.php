<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAdminSubjectClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adminsubject_class', function (Blueprint $table) {
            $table->dropForeign(['adminsubject_id']);
            $table->foreign('adminsubject_id')->references('id')->on('teacher_subject');
            $table->renameColumn('adminsubject_id', 'teachersubject_id');
        });

        Schema::rename('adminsubject_class', 'teachersubject_class');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('teachersubject_class', 'adminsubject_class');

        Schema::table('adminsubject_class', function (Blueprint $table) {
            $table->dropForeign(['teachersubject_id']);
            $table->foreign('teachersubject_id')->references('id')->on('teacher_subject');
            $table->renameColumn('teachersubject_id', 'adminsubject_id');
        });
    }
}
