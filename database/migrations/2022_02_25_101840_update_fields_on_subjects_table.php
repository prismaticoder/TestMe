<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsOnSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->renameColumn('alias', 'slug');
            $table->renameColumn('subject_name', 'name');
            $table->string('code', 3)->after('name')->unique()->nullable();

            $table->index('slug');
        });

        //update code on existing subject table.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->renameColumn('slug', 'alias');
            $table->renameColumn('name', 'subject_name');
            $table->dropColumn('code');
        });
    }
}
