<?php

namespace CustomHelpers;

use File,
    Redirect,
    Lang,
    stdClass;

class CustomLanguageHelper {

    /**
     * Get list of registered languages
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public static function getListOfLanguages()
    {
        try
        {
            $language_dirs = File::directories(app_path('lang'));
        }
        catch(InvalidArgumentException $e)
        {
            return Redirect::to('/')->with('error', $e->getMessage());
        }

        $languages = array();
        foreach($language_dirs as $language_dir)
        {
            $language_code = basename($language_dir);

            if(Lang::has('language.name', $language_code))
            {
                $languages[$language_code] = Lang::get('language.name', array(), $language_code);
            }
        }

        //setlocale(LC_COLLATE, 'csy');
        uasort($languages, 'strcoll');

        return $languages;
    }
}
?>