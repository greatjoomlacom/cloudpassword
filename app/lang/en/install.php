<?php

return array(
    'document_title' => 'Installation',
    'article_title' => 'Installation',
    'article_title_icon' => 'icon-gear',

    'prerequisites' => array(
        'document_title' => 'Installation - Step 1 - Prerequisites',
        'article_title' => 'Installation - Step 1 - Prerequisites',
    ),

    'db' => array(
        'establish' => array(
            'document_title' => 'Installation - Step 2 - Establish database connection',
            'article_title' => 'Installation - Step 2 - Establish database connection',
        ),
        'install' => array(
            'document_title' => 'Installation - Step 3 - Install database tables',
            'article_title' => 'Installation - Step 3 - Install database tables',
        ),
    ),

    'account' => array(
        'document_title' => 'Installation - Step 4 - Super User Account',
        'article_title' => 'Installation - Step 4 - Super User Account',
        'article_title_icon' => 'icon-user',
    ),

    'finish' => array(
        'document_title' => 'Installation - Done',
        'article_title' => 'Installation - Done',
        'article_title_icon' => 'icon-ok-circle',
    ),

    'status' => array(
        'error' => array(
            'application_installed' => 'Application is already installed. You don\'t need to install it again.',
            'prerequirements' => 'Your server does not match some prerequirements.',
            'database' => array(
                'empty_value' => 'Some database details are missing. Please check the form and make sure all fields are set up.',
                'connection' => 'It is not possible to connect to your database. Please check the connection details. Database error:<br />:error',
                'sql_file_not_found' => 'SQL installation file not found<br />:path',
                'sql_file_not_readable' => 'SQL installation file is not readable<br />:path',
                'query_error' => 'The SQL query bellow can not be run. Installation will not continue.<br />:query',
            ),
            'account' => array(
                'passwords_do_not_match' => 'Passwords do not match. Please try it again.',
            ),
        ),
    ),

    'form' => array(
        'button' => array(
            'install_db' => 'Install database',
            'save_account' => 'Create an account',
            'install' => 'Install',
            'establish_database_connection' => 'Establish database connection',
            'install_database_tables' => 'Install database tables',
        ),
    ),
);
