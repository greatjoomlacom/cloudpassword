<?php

namespace CustomHelpers;

use Whoops\Exception\ErrorException;

class CustomPathHelper {

    /**
     * Function to strip additional / or \ in a path name.
     * Based on Joomla! JPath::clean() function
     *
     * @param $path
     * @param string $ds
     * @return mixed|string
     */
    public static function clean($path, $ds = DIRECTORY_SEPARATOR)
    {
        if (!is_string($path) && !empty($path))
        {
            throw new ErrorException('JPath::clean: $path is not a string.');
        }

        $path = trim($path);

        if (empty($path))
        {
            $path = dirname(app_path());
        }
        // Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
        // If dealing with a UNC path don't forget to prepend the path with a backslash.
        elseif (($ds == '\\') && ($path[0] == '\\' ) && ( $path[1] == '\\' ))
        {
            $path = "\\" . preg_replace('#[/\\\\]+#', $ds, $path);
        }
        else
        {
            $path = preg_replace('#[/\\\\]+#', $ds, $path);
        }

        return $path;
    }

    /**
     * Strips the last extension off of a file name
     * Based on Joomla! JFile::stripExt() function
     *
     * @param $file
     * @return mixed
     */
    public static function stripExt($file)
    {
        return preg_replace('#\.[^.]*$#', '', $file);
    }
}
?>