<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use CustomHelpers\CustomConfigHelper;

class Install3Command extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'install:3';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = 'Application installation - step 3.';

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
            return $this->error('Please initial your installation with "install:1" command.');
        }

        if(!File::exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step2'))
        {
            return $this->error('Please run "install:2" command first.');
        }

        if(File::exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step3'))
        {
            return $this->error('Application is already installed.');
        }

        $this->info('Post installation configuration...');
        $this->line('');

        // publish config file
        $this->info('Publishing Juy Profiler config file...');
        $this->call('config:publish', array('package' => 'juy/profiler'));
        $this->line('');

        $this->info('Publishing Sentry config file...');
        $this->call('config:publish', array('package' => 'cartalyst/sentry'));
        $this->line('');

        // modify file
        $this->info('Modifying Sentry config file...');
        $this->line('');
        $config_file = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'cartalyst' . DIRECTORY_SEPARATOR . 'sentry' . DIRECTORY_SEPARATOR . 'config.php';

        $config_file_output = \File::get($config_file);
        $config_file_output = str_replace(
            array(
                "'model' => 'Cartalyst\Sentry\Groups\Eloquent\Group'",
                "'model' => 'Cartalyst\Sentry\Users\Eloquent\User'",
            ),
            array(
                "'model' => 'Group'",
                "'model' => 'User'",
            ),
            $config_file_output
        );

        File::put($config_file, $config_file_output);

        // change shared configuration
        CustomConfigHelper::setConfig('shared',
            array(
                'app.debug' => false, // disable debug
                'app.locale' => 'en', // set default locale
                //'app.url' => URL::to('/') . '/' . Config::get('app.locale'), // set URL to default
                'mode' => 'production',
            ),
            'config',
            true
        );

        $this->call('key:generate');
        $this->line('');

        $this->call('storage:clean');
        $this->line('');

        // write "installed" file
        File::put(storage_path('meta') . DIRECTORY_SEPARATOR . 'installed', '');

        // remove statuses files
        File::delete(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step1');
        File::delete(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step2');

        $this->line('');
        $this->line('# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #');
        $this->line('');
        $this->info('Application has been succesffully installed. Bring it on!');
        $this->line('');
        $this->line('# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #');

        return true;
	}
}
