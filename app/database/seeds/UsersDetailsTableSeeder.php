<?php

use CustomHelpers\CustomSecurityHelper;

class UsersDetailsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        DB::table('users_details')->delete();

        UsersDetailsModel::insert(
            array(
                array(
                    'user_id' => $this->increaseNumber(),
                    'first_name' => 'Daniel',
                    'last_name' => 'Rataj',
                    'language' => 'en',
                ),
                /*
                array(
                    'user_id' => $this->increaseNumber(),
                    'first_name' => 'Administrator',
                    'last_name' => 'Admin',
                    'language' => 'en',
                ),
                */
            )
        );

        /*
        // random data generator
        $random_counter = 1;
        while($random_counter <= 200)
        {
            UsersDetailsModel::insert(
                array(
                    array(
                        'user_id' => $this->increaseNumber(),
                        'first_name' => \CustomHelpers\RandomHelper::firstName(),
                        'last_name' => \CustomHelpers\RandomHelper::lastName(),
                        'language' => 'en',
                    )
                )
            );

            $random_counter++;
        }
        */

        $this->command->info('#__users_details table seeded!');
	}

    private function increaseNumber()
    {
        return ++$this->id_counter;
    }

}