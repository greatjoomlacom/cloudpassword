<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \Symfony\Component\Finder\Finder;
use CustomHelpers\CustomPathHelper;

class StorageCleanCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'storage:clean';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clean up app/storage/* directories.';

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
        $this->info('Cleaning [' . CustomPathHelper::clean(app_path('storage')) . '] folder...');

        // delete /app/storage/cache|logs|views|sessions directories
        $directories_to_clean = array(
            app_path('storage/cache'),
            app_path('storage/logs'),
            app_path('storage/meta'),
            app_path('storage/sessions'),
            app_path('storage/views'),
        );

        foreach($directories_to_clean as $dir)
        {
            if(!File::isDirectory($dir)) continue;

            $files = Finder::create()->files()->in($dir)->notName('.gitignore')->notName('#\.json|down|installed$#');

            if(!$files->count()) continue;

            foreach($files as $file)
            {
                if(!File::delete($file))
                {
                    continue;
                }
            }
        }

        return $this->info('Done.');
	}
}