<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModeDemoCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mode:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Switch application to demo mode.";

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
        \CustomHelpers\CustomConfigHelper::setConfig(
            'shared',
            array(
                'mode' => 'demo'
            ),
            'config',
            true // ignore environment
        );

        return $this->info("Application switched to demo mode.");
    }
}
