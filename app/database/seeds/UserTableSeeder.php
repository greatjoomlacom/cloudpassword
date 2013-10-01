<?php

use CustomHelpers\CustomSecurityHelper;

class UserTableSeeder extends Seeder {

    /**
     * Counter
     * @var int
     */
    private $id_counter = 0;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        DB::table('users')->delete();
        DB::table('users_groups')->delete();

        /*
        // Super User
        $user = Sentry::getUserProvider()->create(
            array(
                'id' => $this->increaseNumber(),
                'email'    => 'superuser@superuser.com',
                'password' => 'superuser@superuser.com',
                'activated' => 1,
            )
        );

        $group = Sentry::getGroupProvider()->findById(1); // Registered
        $user->addGroup($group);

        $group = Sentry::getGroupProvider()->findById(1000); // super user
        $user->addGroup($group);


        // random data generator
        $random_counter = 1;
        while($random_counter <= 200)
        {
            $user = Sentry::getUserProvider()->create(
                array(
                    'id' => $this->increaseNumber(),
                    'email'    => "user_" . $random_counter . "_" . CustomSecurityHelper::random_key(rand(5, 10), true, true) . "@user.com",
                    'password' => "user_$random_counter@user.com",
                    'activated' => rand(0, 1),
                )
            );
            $group = Sentry::getGroupProvider()->findById(rand(5, 50)); // add to random group
            $user->addGroup($group);

            $random_counter++;
        }
        */

        $this->command->info('#__users table seeded!');
	}

    private function increaseNumber()
    {
        return ++$this->id_counter;
    }

}