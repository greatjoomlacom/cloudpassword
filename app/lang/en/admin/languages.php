<?php

return array(
    'document_title' => 'Languages',
    'article_title' => 'Languages',
    'article_title_icon' => 'icon-flag',

    'default_language' => 'Default language',

    'form' => array(
        'error' => array(
            'required' => array(
                'name' => 'Field with language code is missing.',
            ),
        ),
    ),

    'delete' => array(
        'status' => array(
            'success' => 'Language <b>:language</b> has been successfully deleted.',
            'error' => array(
                'delete_default' => 'It is not possible to remove default language.',
            ),
        ),
    ),

    'update' => array(
        'article_title' => 'Languages - :language',

        'form' => array(
            'button' => array(
                'update' => 'Update language'
            ),
        ),
        'status' => array(
            'error' => array(
                'missing_lang_directory' => 'Language directory does not exists:<br /><i>:path</i>',
                'copy' => 'Copy of file failed. Please check permissions on file:<br />:path',
                'copy_revert' => 'It is not possible to revert file back. Please check permissions on file:<br />:path',
                'put' => 'It is not possible to create a new configuration file. Please check permissions on file:<br />:path',
            ),
            'success' => 'Language file <b>:language</b> has been successfully update.',
        ),
    ),

    'switch' => array(
        'status' => array(
            'error' => array(
                'switch_to_default' => 'You can not switch to <b>:language</b> as it is already setup in configuration file.',
            ),
            'success' => 'Locale has been switched to <b>:language</b> language.',
        ),
    ),
);
