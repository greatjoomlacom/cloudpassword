<?php

use CustomHelpers\CustomSecurityHelper;

class PasswordsTableSeeder extends Seeder {

    /**
     * Counter
     * @var int
     */
    private $id_counter = 0;

    public function run()
    {
        DB::table('passwords')->delete();
        /*
                PasswordsModel::insert(
                    array(
                        array(
                            'id' => $this->increaseNumber(),
                            'title' => 'GMail',
                            'username' => 'test@gmail.com',
                            'password' => Crypt::encrypt('test@gmail.com'),
                            'note' => 'Password for GMail account test@gmail.com.',
                            'category_id' => 2,
                            'user_id' => 1,
                            'access' => '',
                            'created_at' => new DateTime(),
                        ),
                        array(
                            'id' => $this->increaseNumber(),
                            'title' => 'Home PC',
                            'username' => 'home_pc',
                            'password' => Crypt::encrypt('home_pc'),
                            'note' => 'My PC at home.',
                            'category_id' => 3,
                            'user_id' => 1,
                            'access' => '',
                            'created_at' => new DateTime(),
                        ),

                        array(
                            'id' => $this->increaseNumber(),
                            'title' => 'Work PC',
                            'username' => 'work_pc',
                            'password' => Crypt::encrypt('work_pc'),
                            'note' => 'My PC at work.',
                            'category_id' => 3,
                            'user_id' => 2,
                            'access' => '',
                            'created_at' => new DateTime(),
                        ),


            )
        );
        */

        $this->command->info('#__passwords table seeded!');
    }

    private function increaseNumber()
    {
        return ++$this->id_counter;
    }
}