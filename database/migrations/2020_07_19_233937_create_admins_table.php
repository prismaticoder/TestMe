<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->integer('AdminRoleId')->unsigned();
            $table->foreign('AdminRoleId')->references('id')->on('roles')->nullable();
            $table->integer('adminClassId')->unsigned();
            $table->foreign('adminClassId')->references('id')->on('classes')->nullable();
            $table->integer('adminSubjectId')->unsigned();
            $table->foreign('adminSubjectId')->references('id')->on('subjects')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
