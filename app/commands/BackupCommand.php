<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DbCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:backup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Backup database';

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
		// And seed it
		echo PHP_EOL.'Dumping DB...'.PHP_EOL;

        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'mysqldumper.class.php';

        $dump = new MysqlDumper(
            Config::get('database.connections.mysql.host'),
            Config::get('database.connections.mysql.username'),
            Config::get('database.connections.mysql.password'),
            Config::get('database.connections.mysql.database')
        );
        $dump = $dump->createDump();

        $output = $dump;

        $path_to_backup_dir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'backup';

        if(!File::isDirectory($path_to_backup_dir))
        {
            if(!File::makeDirectory($path_to_backup_dir))
            {
                echo 'Failed.' . PHP_EOL.PHP_EOL;
                exit;
            }
        }
        $path_to_sql_backup_dir = $path_to_backup_dir . DIRECTORY_SEPARATOR . 'sql';

        if(!File::isDirectory($path_to_sql_backup_dir))
        {
            if(!File::makeDirectory($path_to_sql_backup_dir))
            {
                echo 'Failed.' . PHP_EOL.PHP_EOL;
                exit;
            }
        }

        if(!File::put($path_to_sql_backup_dir . DIRECTORY_SEPARATOR . 'backup_' . time() . '.sql', $output))
        {
            echo 'Failed.' . PHP_EOL.PHP_EOL;
            exit;
        }

		echo 'Done.' . PHP_EOL.PHP_EOL;
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
