<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('users_details', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';

            $table->integer('user_id', false, true)->unique()->index();
            $table->foreign('user_id', 'user_details_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('language');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('users_details');
	}

}