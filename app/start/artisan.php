<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

foreach(File::files(app_path('commands')) as $file)
{
    $command = preg_replace('#\.[^.]*$#', '', basename($file));

    if(class_exists($command))
    {
        $command = new $command;
        Artisan::add($command);
    }
}