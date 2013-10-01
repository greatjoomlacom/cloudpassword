<?php

class MenuTableSeeder extends Seeder {

    private $ordering = array(
        'default' => 0,
        'account' => 0,
    );

	/**
	 * Run the database seeds.
	 * @return void
	 */
	public function run()
	{
        DB::table('menu')->delete();

        MenuModel::insert(
            array(
                array(
                    'context' => 'categories',
                    'title' => 'menu.items.admin.categories',
                    'link' => 'admin/categories',
                    'type' => 'link',
                    'icon' => 'icon-folder-open-alt',
                    'group' => 'admin',
                    'position' => 'header',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'categories',
                            'categories.view',
                            'categories.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'users',
                    'title' => 'menu.items.admin.users',
                    'link' => 'admin/users',
                    'type' => 'link',
                    'icon' => 'icon-user',
                    'group' => 'admin',
                    'position' => 'header',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'users',
                            'users.view',
                            'users.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'groups',
                    'title' => 'menu.items.admin.groups',
                    'link' => 'admin/groups',
                    'type' => 'link',
                    'icon' => 'icon-group',
                    'group' => 'admin',
                    'position' => 'header',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'groups',
                            'groups.view',
                            'groups.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'languages',
                    'title' => 'menu.items.admin.languages',
                    'link' => 'admin/languages',
                    'type' => 'link',
                    'icon' => 'icon-flag',
                    'group' => 'admin',
                    'position' => 'header',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'languages',
                            'languages.view',
                            'languages.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'configuration',
                    'title' => 'menu.items.admin.configuration',
                    'link' => 'admin/configuration',
                    'type' => 'link',
                    'icon' => 'icon-gear',
                    'group' => 'admin',
                    'position' => 'header',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'configuration',
                            'configuration.view',
                            'configuration.edit',
                        )
                    ),
                ),

                array(
                    'context' => 'account',
                    'title' => 'menu.items.account.detail',
                    'link' => 'account',
                    'type' => 'account',
                    'group' => 'account',
                    'icon' => 'icon-user',
                    'position' => 'header',
                    'ordering' => $this->getOrdering('account'),
                    'access' => '',
                ),
                array(
                    'context' => 'account',
                    'title' => 'menu.items.account.logout',
                    'link' => 'account/logout',
                    'type' => 'link',
                    'group' => 'account',
                    'icon' => 'icon-user',
                    'position' => 'header',
                    'ordering' => $this->getOrdering('account'),
                    'access' => '',
                ),

                array(
                    'context' => 'categories',
                    'title' => 'menu.items.admin.categories',
                    'link' => 'admin/categories',
                    'type' => 'link',
                    'icon' => 'icon-folder-open-alt',
                    'group' => 'dashboard',
                    'position' => 'dashboard',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'categories',
                            'categories.view',
                            'categories.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'users',
                    'title' => 'menu.items.admin.users',
                    'link' => 'admin/users',
                    'type' => 'link',
                    'icon' => 'icon-user',
                    'group' => 'dashboard',
                    'position' => 'dashboard',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'users',
                            'users.view',
                            'users.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'groups',
                    'title' => 'menu.items.admin.groups',
                    'link' => 'admin/groups',
                    'type' => 'link',
                    'icon' => 'icon-group',
                    'group' => 'dashboard',
                    'position' => 'dashboard',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'groups',
                            'groups.view',
                            'groups.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'languages',
                    'title' => 'menu.items.admin.languages',
                    'link' => 'admin/languages',
                    'type' => 'link',
                    'icon' => 'icon-flag',
                    'group' => 'dashboard',
                    'position' => 'dashboard',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'languages',
                            'languages.view',
                            'languages.edit',
                        )
                    ),
                ),
                array(
                    'context' => 'configuration',
                    'title' => 'menu.items.admin.configuration',
                    'link' => 'admin/configuration',
                    'type' => 'link',
                    'icon' => 'icon-gear',
                    'group' => 'dashboard',
                    'position' => 'dashboard',
                    'ordering' => $this->getOrdering(),
                    'access' => json_encode(
                        array(
                            'configuration',
                            'configuration.view',
                            'configuration.edit',
                        )
                    ),
                )
            )
        );

        $this->command->info('#__menu table seeded!');
	}

    /**
     * Get ordering value
     * @var $group
     * @return int
     */
    private function getOrdering($group = 'default')
    {
        return ++$this->ordering[$group];
    }

}