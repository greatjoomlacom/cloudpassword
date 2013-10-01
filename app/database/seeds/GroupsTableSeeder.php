<?php

use CustomHelpers\CustomSecurityHelper;

class GroupsTableSeeder extends Seeder {

    private $professions = array();

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        DB::table('groups')->delete();

        // Super User
        $group = Sentry::getGroupProvider()->create(
            array(
                'id' => 1000,
                'name' => 'Super User',
                'permissions' => array(
                    'superuser' => 1,
                    'categories' => 1,
                    'categories.view' => 1,
                    'categories.edit' => 1,
                    'passwords' => 1,
                    'passwords.view' => 1,
                    'passwords.edit' => 1,
                    'configuration' => 1,
                    'configuration.view' => 1,
                    'configuration.edit' => 1,
                    'groups' => 1,
                    'groups.view' => 1,
                    'groups.edit' => 1,
                    'users' => 1,
                    'users.view' => 1,
                    'users.edit' => 1,
                    'languages' => 1,
                    'languages.view' => 1,
                    'languages.edit' => 1,
                ),
            )
        );

        // Registered
        $group = Sentry::getGroupProvider()->create(
            array(
                'id' => 1,
                'name' => 'Registered',
                'permissions' => array(
                    'categories' => 1,
                    'categories.view' => 1,
                    'categories.edit' => 1,
                    'passwords' => 1,
                    'passwords.view' => 1,
                    'passwords.edit' => 1,
                ),
            )
        );

        /*
        $permissions_pick = array(
            'passwords' => 1,
            'passwords.view' => 1,
            'passwords.edit' => 1,
            'users' => rand(0,1),
            'users.view' => rand(0,1),
            'users.edit' => rand(0,1),
            'languages' => rand(0,1),
            'languages.view' => rand(0,1),
            'languages.edit' => rand(0,1),
        );

        // random data generator for groups
        $random_counter = 5;
        while($random_counter <= 50)
        {
            $profession = \CustomHelpers\RandomHelper::profession();
            if(in_array($profession, $this->professions))
            {
                // make sure group name is unique
                $profession = \CustomHelpers\RandomHelper::profession();
            }

            $group = Sentry::getGroupProvider()->create(
                array(
                    'id' => $random_counter,
                    'name'    => $profession,
                    'permissions' => $this->shuffle_assoc($permissions_pick),
                )
            );

            $this->professions[] = $profession;

            $random_counter++;
        }
        */

        $this->command->info('#__groups table seeded!');
	}

    private function shuffle_assoc($array)
    {
        $keys = array_keys($array);
        shuffle($keys);
        return array_merge(array_flip($keys), $array);
    }



}