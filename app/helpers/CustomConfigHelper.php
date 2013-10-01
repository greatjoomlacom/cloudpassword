<?php

namespace CustomHelpers;

use File;
use Redirect;
use Lang;
use DateTime;
use Illuminate\Support\Facades\Config;

class CustomConfigHelper {

    /**
     * Change file, backup old one
     * @param string $file
     * @param array $data
     * @param string $directory
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    /*
    public static function changeFile($file = '', $data = array(), $directory = 'config')
    {
        if(!$file) return Redirect::to('/');

        $filepath = CustomPathHelper::clean(app_path($file) . '.php');

        if(!File::isFile($filepath)) return Redirect::to('/');

        if (!File::isWritable($filepath))
        {
            return Redirect::to('/');
        }

        // backup dir
        $backup_dir = CustomPathHelper::clean(dirname(app_path($file)) . DIRECTORY_SEPARATOR . 'backup');

        if(!File::isDirectory($backup_dir))
        {
            if(!File::makeDirectory($backup_dir))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.filesystem.write_dir', array('path' => $backup_dir)));
            }
        }

        // backup old config
        $date = new DateTime();
        $copied_filename = basename($file) . '-backup-' . $date->format('Y-m-d_h-m-s') . '.php';
        $copied_filename_path = CustomPathHelper::clean($backup_dir . DIRECTORY_SEPARATOR . $copied_filename);

        // copy failed
        if(!File::copy($filepath, $copied_filename_path))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.copy', array('path' => $filepath)));
        }

        $output = File::get($filepath);

        foreach(array_dot($data) as $key=>$value)
        {
            $config_value = \Config::get(basename($file) . '.' . $key);
            if(is_bool($config_value))
            {
                $config_value = ($config_value ? 'true' : 'false');
            }

            if($key === 'locale')
            {
                // get locale from DEFINE
                $config_value = CONFIG_APP_LOCALE;
            }

            $regular = "#('" . preg_quote(last(explode('.', $key))) . "'\s*=>\s*)+'?" . preg_quote($config_value) . "'?,?#s";

            $replace = "$1'" . '###' . "',";
            if(is_int($value) or is_bool($value))
            {
                if(is_bool($value))
                {
                    $value = ($value ? 'true' : 'false');
                }
                $replace = "$1" . '###' . ",";
            }

            $output = preg_replace(
                $regular,
                $replace,
                $output
            );

            if(strpos($output, '###') !== false)
            {
                $output = str_replace('###', $value, $output);
            }

        }

        // write new file data
        if(!File::put($filepath, $output))
        {
            // failed

            // revert old configuration back
            if(!File::copy($copied_filename_path, $filepath))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.filesystem.copy_file', array('path' => $copied_filename_path)));
            }

            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.write_file', array('path' => dirname($filepath))));
        }

        return true;
    }
    */

    /**
     * Set config value
     * @param string $context
     * @param string $directory
     * @param boolean $ignore_environment
     * @param array $data
     * @return bool
     */
    public static function setConfig($context = '', $data = array(), $directory = 'config', $ignore_environment = false)
    {
        if(!$context) return false;
        if(!is_array($data)) return false;

        $environment = '';
        if(!$ignore_environment)
        {
            $environment = \App::environment();
            if($environment) $environment . DIRECTORY_SEPARATOR;
        }

        $config_file = CustomPathHelper::clean(app_path($directory . DIRECTORY_SEPARATOR . $environment . $context . '.php'));

        if(!File::exists($config_file))
        {
            return false;
        }

        // backup dir
        $backup_dir = CustomPathHelper::clean(dirname($config_file) . DIRECTORY_SEPARATOR . 'backup');

        if(!File::isDirectory($backup_dir))
        {
            if(!File::makeDirectory($backup_dir))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.filesystem.write_dir', array('path' => $backup_dir)));
            }
        }

        // backup old config
        $date = new DateTime();
        $copied_filename = basename($config_file) . '-backup-' . $date->format('Y-m-d_h-m-s') . '.php';
        $copied_filename_path = CustomPathHelper::clean($backup_dir . DIRECTORY_SEPARATOR . $copied_filename);

        // copy failed
        if(!File::copy($config_file, $copied_filename_path))
        {
            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.copy', array('path' => $config_file)));
        }

        if(!$content = File::getRequire($config_file))
        {
            return false;
        }

        foreach($data as $name=>$value)
        {
            if(is_bool($value)) $value = ($value ? 'true' : 'false');
            array_set($content, $name, $value);
        }

        $new_content = "<?php\n\nreturn\n\n" . var_export($content, true) . ";\n\n?>";

        $new_content = str_replace(
            array(
                "'false'",
                "'true'",
            ),
            array(
                "false",
                "true",
            ),
            $new_content);


        /*
        if(!$content = File::get($config_file))
        {
            return false;
        }

        $lines = explode("\n", $content);

        $content = array();
        foreach($lines as $line_key=>$line)
        {
            if(!empty($line))
            {
                $founded = false;
                foreach($data as $key=>$value)
                {
                    // make sure boolean and integer are both set to "string"
                    if(is_bool($value))
                    {
                        $value = ($value ? 'true' : 'false');
                    }
                    if(is_numeric($value))
                    {
                        $value = "$value";
                    }

                    $value = (string)$value;

                    // is dotted
                    if(strpos($key, '.') !== false)
                    {
                        $config_keys_to_explore = explode('.', $key);
                        $key = array_pop($config_keys_to_explore);
                    }

                    if(strpos($line, $key))
                    {
                        $config_value = Config::get(
                            $context . '.' . (isset($config_keys_to_explore) ? implode('.', $config_keys_to_explore) : '') . '.' . $key
                        );

                        if(is_bool($config_value))
                        {
                            $config_value = ($config_value ? 'true' : 'false');
                        }
                        if(is_numeric($config_value))
                        {
                            $config_value = "$config_value";
                        }

                        $founded = true;
                        $line = str_replace($config_value, $value, $line);

                        $content[] = $line;
                        break;
                    }
                }

                if($founded)
                {
                    unset($data[$key]);
                    continue;
                }
            }

            $content[] = $line;
        }
        */

        // write new file data
        if(!File::put($config_file, $new_content))
        {
            // failed

            // revert old configuration back
            if(!File::copy($copied_filename_path, $config_file))
            {
                return Redirect::back()->with('error', Lang::get('shared.error.filesystem.copy_file', array('path' => $copied_filename_path)));
            }

            return Redirect::back()->with('error', Lang::get('shared.error.filesystem.write_file', array('path' => dirname($config_file))));
        }

        return true;
    }
}
?>