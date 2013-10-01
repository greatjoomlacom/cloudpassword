<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DBDropCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:drop';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Drop all tables.';

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
        if (!$this->confirm('Are you sure you want to drop all tables from your [' . DB::getDatabaseName() . '] database? [yes|no]', false))
        {
            return false;
        }
        $this->line('');

        //$query = "SELECT concat('DROP TABLE ', table_name, ';') FROM information_schema.tables WHERE table_schema = '" . DB::getDatabaseName() . "';";
        $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB::getDatabaseName() . "';";

        if(!$results = DB::select($query))
        {
            return $this->error('There are no queries to drop.');
        }

        foreach($results as &$result)
        {
            $array = array_values((array)$result);
            $result = $array[0];
        }

        $results = array_reverse($results); // must be in reverse order - I don't know why

        foreach($results as $result)
        {
            $array = array_values((array)$result);
            $result = $array[0];

            $result_without_prefix = str_replace(DB::getTablePrefix(), '', $result);

            DB::statement("SET FOREIGN_KEY_CHECKS = 0;");
            \Schema::drop($result_without_prefix);
        }

        $this->info('Database [' . DB::getDatabaseName() . '] truncated.');
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
