<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->renameColumn('base_score', 'aggregate_score');
            $table->string('code')->unique()->nullable();
            $table->timestamp('started_at')->after('date')->nullable();
            $table->foreignId('duplicated_from')->after('minutes')->nullable()->constrained('exams');
            $table->foreignId('created_by')->after('duplicated_from')->nullable()->constrained('teachers')->onDelete('SET NULL');

            $table->dropColumn(['hasStarted']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->renameColumn('aggregate_score', 'base_score');
            $table->dropColumn(['code', 'started_at', 'duplicated_from', 'created_by']);
            $table->boolean('hasStarted')->default(0);
        });
    }
}
