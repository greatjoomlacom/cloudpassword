<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DBForeignKeys extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:foreign';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'List all foreign keys in table.';

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
        $query = "SELECT constraint_name, table_name FROM information_schema.table_constraints WHERE constraint_type = 'FOREIGN KEY' AND table_schema = DATABASE() ORDER BY constraint_name;";

        if(!$results = DB::select($query))
        {
            return $this->info('No foreign keys founded in [' . DB::getDatabaseName() . '] database.');
        }

        foreach($results as $result)
        {
            $this->info($result->table_name . ' => ' . $result->constraint_name);
        }
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
