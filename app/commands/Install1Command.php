<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use CustomHelpers\CustomConfigHelper;

class Install1Command extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'install:1';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = 'Application installation - step 1.';

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

        if(File::exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step1'))
        {
            return $this->error('Please continue your installation with "install:2" command.');
        }

        // ask first
        if (!$this->confirm('Are you sure you want to install this application? [yes|no]', false))
        {
            return false;
        }
        $this->line('');

        $this->info('Installation started...');
        $this->line('');

        // ask for database credentials

        $this->info('There are two things we have to know. First one is the database connection.');
        $this->line('');

        $database_host = Config::get('database.connections.mysql.host', 'localhost');
        $database_host = $this->ask('Database host' . ($database_host ? ' [' . $database_host . ']' : '') . ': ', $database_host);
        if(!$database_host)
        {
            return $this->error('Database host is required!');
        }

        $database_database = Config::get('database.connections.mysql.database');
        $database_database = $this->ask('Database name' . ($database_database ? ' [' . $database_database . ']' : '') . ': ', $database_database);
        if(!$database_database)
        {
            return $this->error('Database name is required!');
        }

        $database_username = Config::get('database.connections.mysql.username');
        $database_username = $this->ask('User' . ($database_username ? ' [' . $database_username . ']' : '') .': ', $database_username);
        if(!$database_username)
        {
            return $this->error('Database user is required!');
        }

        $database_password = Config::get('database.connections.mysql.password');
        $database_password = $this->ask('Database password' . ($database_password ? ' [' . $database_password . ']' : '') . ': ', $database_password);

        $database_charset = Config::get('database.connections.mysql.charset', 'utf8');
        $database_charset = $this->ask('Database charset' . ($database_charset ? ' [' . $database_charset . ']' : '') . ': ', $database_charset);
        if(!$database_charset)
        {
            return $this->error('Database charset is required!');
        }

        $database_collation = Config::get('database.connections.mysql.collation', 'utf8_general_ci');
        $database_collation = $this->ask('Database collation' . ($database_collation ? ' [' . $database_collation . ']' : '') . ': ', $database_collation);
        if(!$database_collation)
        {
            return $this->error('Database collation is required!');
        }

        $database_prefix = Config::get('database.connections.mysql.prefix');
        if(!$database_prefix) $database_prefix = CustomHelpers\CustomSecurityHelper::random_key(4, true, true) . '_';

        $database_prefix = $this->ask('Database prefix' . ($database_prefix ? ' [' . $database_prefix . ']' : '') . ': ', $database_prefix);
        if(!$database_prefix)
        {
            return $this->error('Database prefix is required!');
        }

        // check for valid DB connection
        try {
            $dbh = new PDO(
                'mysql:host=' . $database_host . ';dbname=' . $database_database,
                $database_username,
                $database_password
            );
            $dbh = null; // close connection
        } catch (PDOException $e)
        {
            return $this->error('Database details you provided are wrong. Please run installation again.');
        }
        $this->line('');

        $this->info('Saving database configuration...');
        $this->line('');

        // write database configuration to shared.php configuration file
        CustomConfigHelper::setConfig('shared',
            array(
                'database.connections.mysql.host' => $database_host,
                'database.connections.mysql.database' => $database_database,
                'database.connections.mysql.username' => $database_username,
                'database.connections.mysql.password' => $database_password,
                'database.connections.mysql.charset' => $database_charset,
                'database.connections.mysql.collation' => $database_collation,
                'database.connections.mysql.prefix' => $database_prefix,
            ),
            'config',
            true
        );

        File::put(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installation_step1', '');

        $this->info('Database configuration successfully saved. Please continue with "install:2" command.');
        return $this->line('');
	}
}
