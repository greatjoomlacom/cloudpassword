<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControllersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('controllers', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';

			$table->increments('id');

            $table->string('uri')->unique();
            $table->string('action');
            $table->integer('ordering');
            $table->text('comment');

            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('controllers');
	}

}
