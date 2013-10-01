<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('name')->unique()->index();
            $table->string('slug');
            $table->text('note');

            $table->integer('user_id', false, true)->index();
            $table->foreign('user_id', 'categories_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('access');

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
        Schema::dropIfExists('categories');
    }

}
