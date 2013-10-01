<?php

class CategoriesTableSeeder extends Seeder {

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
        DB::table('categories')->delete();

        CategoriesModel::insert(
            array(
                array(
                    'id' => $this->increaseNumber(),
                    'name' => 'General',
                    'slug' => 'general',
                    'note' => 'There is a place for category note.',
                    'user_id' => 1,
                    'created_at' => new DateTime(),
                ),
                array(
                    'id' => $this->increaseNumber(),
                    'name' => 'Emails',
                    'slug' => 'emails',
                    'note' => 'There is a place for Emails category note.',
                    'user_id' => 1,
                    'created_at' => new DateTime(),
                ),
                array(
                    'id' => $this->increaseNumber(),
                    'name' => 'Windows',
                    'slug' => 'windows',
                    'user_id' => 1,
                    'note' => '',
                    'created_at' => new DateTime(),
                ),
                array(
                    'id' => $this->increaseNumber(),
                    'name' => 'Homebanking',
                    'slug' => 'homebanking',
                    'user_id' => 1,
                    'note' => '',
                    'created_at' => new DateTime(),
                ),
                array(
                    'id' => $this->increaseNumber(),
                    'name' => 'Network',
                    'slug' => 'network',
                    'note' => 'Other note displayed here.',
                    'user_id' => 1,
                    'created_at' => new DateTime(),
                ),
                array(
                    'id' => $this->increaseNumber(),
                    'name' => 'Network test@test.com',
                    'slug' => 'network',
                    'note' => 'Other note displayed here.',
                    'user_id' => 2,
                    'created_at' => new DateTime(),
                ),
            )
        );

        $this->command->info('#__categories table seeded!');
	}

    private function increaseNumber()
    {
        return ++$this->id_counter;
    }

}