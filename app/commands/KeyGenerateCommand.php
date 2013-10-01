<?php namespace Illuminate\Foundation\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class KeyGenerateCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Set the application key";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('Generating unique application key...');

        $key = $this->getRandomKey();

        \CustomHelpers\CustomConfigHelper::setConfig(
            'shared',
            array(
                'app.key' => $key
            ),
            'config',
            true // ignore environment
        );

        return $this->info("Done");
    }


    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return Str::random(32);
    }

}
