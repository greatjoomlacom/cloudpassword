<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passwords', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('title');
            $table->string('username');
            $table->text('password');
            $table->text('note');

            $table->integer('category_id', false, true)->index();
            $table->foreign('category_id', 'passwords_category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id', false, true)->index();
            $table->foreign('user_id', 'passwords_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('url');
            $table->string('access');

            $table->dateTime('expire');

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
        Schema::dropIfExists('passwords');
    }

}
