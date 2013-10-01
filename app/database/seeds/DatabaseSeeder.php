<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
        $this->call('GroupsTableSeeder');
        //$this->call('UserTableSeeder');
        //$this->call('UsersDetailsTableSeeder');

        $this->call('ControllersTableSeeder');
		$this->call('MenuTableSeeder');
        //$this->call('CategoriesTableSeeder');
        //$this->call('PasswordsTableSeeder');
	}

}