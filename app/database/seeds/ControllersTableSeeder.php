<?php

class ControllersTableSeeder extends Seeder {

    private $ordering = 0;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        DB::table('controllers')->delete();

        ControllersModel::insert(
            array(
                array(
                    'uri' => 'admin/users',
                    'action' => 'Admin\AdminUsersController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),
                array(
                    'uri' => 'admin/groups',
                    'action' => 'Admin\AdminGroupsController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),
                array(
                    'uri' => 'admin/categories',
                    'action' => 'Admin\AdminCategoriesController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),
                array(
                    'uri' => 'admin/languages',
                    'action' => 'Admin\AdminLanguagesController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),
                array(
                    'uri' => 'admin/configuration',
                    'action' => 'Admin\AdminConfigurationController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),

                array(
                    'uri' => 'passwords',
                    'action' => 'PasswordsController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),
                array(
                    'uri' => 'passwords/category/{category}',
                    'action' => 'PasswordsController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),

                array(
                    'uri' => 'account',
                    'action' => 'AccountController',
                    'ordering' => $this->getOrdering(),
                    'comment' => '',
                    'created_at' => new DateTime()
                ),

                array(
                    'uri' => '/',
                    'action' => 'IndexController',
                    'ordering' => $this->getOrdering(),
                    'comment' => 'Default controller',
                    'created_at' => new DateTime()
                ),
            )
        );

        $this->command->info('#__controllers table seeded!');
	}

    /**
     * Get ordering value
     * @return int
     */
    private function getOrdering()
    {
        return ++$this->ordering;
    }

}