<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use CustomHelpers\CustomConfigHelper;

class Install2Command extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'install:2';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = 'Application installation - step 2.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
        if(File::exists(storage_path('meta') . DIRECTORY_SEPARATOR . 'installed'))
        {
            return $this->error('Installation has been already installed.');
        }

        if(!File::exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step1'))
        {
            return $this->error('Please initiate your installation with "install:1" command.');
        }

        if(File::exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step2'))
        {
            return $this->error('Please continue your installation with "install:3" command.');
        }

        $this->info('Installing database...');
        $this->line('');

        // Sentry database table
        $this->call('migrate', array('--package' => 'cartalyst/sentry'));

        // migrate application
        $this->call('migrate');
        $this->line('');

        // seed application with data
        $this->call('db:seed');
        $this->line('');

        File::put(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step2', '');

        $this->info('Database has been successfully installed. Please final your installation with "install:3" command.');
        return $this->line('');

	}
}
