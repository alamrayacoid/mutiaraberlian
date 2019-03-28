<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDUsernamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_username', function (Blueprint $table) {
            $table->increments('u_id');
            $table->string('u_company');
            $table->string('u_username');
            $table->string('u_password');
            $table->tinyInteger('u_level');
            $table->enum('u_user', ['A', 'E']);
            $table->string('u_code');
            $table->dateTime('u_lastlogin');
            $table->dateTime('u_lastlogout');
            $table->dateTime('u_created_at');
            $table->dateTime('u_update_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_username');
    }
}
