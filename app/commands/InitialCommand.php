<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use CustomHelpers\CustomConfigHelper;

class InitialCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'initial';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Restart application to initial state.';

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
        // ask first
        if (!$this->confirm('Are you sure you want to restore your application to initial state? [yes|no]', false))
        {
            return false;
        }
        $this->line('');

        $this->info('Setting up your aplication to initial state...');
        $this->line('');

        $this->call('config:publish', array('package' => 'juy/profiler'));
        $this->line('');

        // migrate Sentry
        $this->call('migrate', array('--package' => 'cartalyst/sentry'));

        // publish config file
        $this->call('config:publish', array('package' => 'cartalyst/sentry'));
        $this->line('');

        // truncate database
        $this->call('db:drop');
        $this->line('');

        CustomConfigHelper::setConfig('config',
            array(
                'groups.model' => 'Group',
                'users.model' => 'User',
            ),
            'config/packages/cartalyst/sentry',
            true // ignore environment
        );

        //\File::put($config_file, $config_file_output);
        $this->line('');

        // remove "installed" file from meta dir if exists
        if(File::exists(storage_path('meta') . DIRECTORY_SEPARATOR . 'installed'))
        {
            File::delete(storage_path('meta') . DIRECTORY_SEPARATOR . 'installed');
        }

        /*
         * Change configuration
         */
        CustomConfigHelper::setConfig('shared',
            array(
                // app.php
                'app.debug' => true, // enable debug
                'app.url' => '', // clean application URL
                'app.locale' => 'en', // locale
                'app.key' => '', // key
                'app.timezone' => 'UTC', // key

                // database.php
                'database.connections.mysql.host' => 'localhost',
                'database.connections.mysql.database' => '',
                'database.connections.mysql.username' => '',
                'database.connections.mysql.password' => '',
                'database.connections.mysql.charset' => 'utf8',
                'database.connections.mysql.collation' => 'utf8_general_ci',
                'database.connections.mysql.prefix' => '',

                // shared.php
                'sitename' => 'Cloud Password',
                'mode' => 'production',
                'master_password.enabled' => false,
                'master_password.passwd_path' => '',
                'protected.Super Users' => 1000,

            ),
            'config',
            true // ignore environment
        );

        $this->line('');

        // clean storage directory
        $this->call('storage:clean');

        // remove config files backup directory if exists
        if(File::isDirectory(app_path('config/backup')))
        {
            File::deleteDirectory(app_path('config/backup')); // delete dir

            // check if dir still exists (incorrect file permissions probably)
            if(File::isDirectory(app_path('config/backup')))
            {
                $this->error('Error. It is not possible to delete "' . app_path('config/backup') . '" directory.');
            }
        }

        $this->line('');

        $this->info('Done! Application has been restored to initial state.');
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
