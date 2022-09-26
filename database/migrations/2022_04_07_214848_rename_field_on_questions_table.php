<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameFieldOnQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->renameColumn('question', 'body');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('isCorrect', 'is_correct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->renameColumn('body', 'question');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->renameColumn('is_correct', 'isCorrect');
        });
    }
}
