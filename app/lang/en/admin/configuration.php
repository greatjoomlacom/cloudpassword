<?php

return array(
    'document_title' => 'Configuration',
    'article_title' => 'Configuration',
    'article_title_icon' => 'icon-gear',

    'danger_notification' => 'Please do not change this configuration unless you know what are you dealing with!',

    'status' =>array(
        'success' => 'Configuration has been successfully updated.',
    ),

    'default' => array(
        'title' => 'Default',
        'site' => array(
            'title' => 'Site',
            'form' => array(
                'label' => array(
                    'sitename' => 'Sitename',
                    'language' => 'Language',
                ),
            ),
        ),
        'layout' => array(
            'title' => 'Layout',
            'form' => array(
                'label' => array(
                    'ui' => array(
                        'theme' => 'jQuery UI Theme',
                    ),
                ),
            ),
        ),
    ),

    'server' => array(
        'title' => 'Server',
        'database' => array(
            'title' => 'Database',
            'driver' => array(
                'mysql' => array(
                    'title' => 'MySQL',
                ),
            ),
            'form' => array(
                'label' => array(
                    'driver' => 'Driver',
                    'host' => 'Host',
                    'database' => 'Database',
                    'username' => 'Username',
                    'password' => 'Password',
                    'charset' => 'Charset',
                    'collation' => 'Collation',
                    'prefix' => 'Prefix',
                ),
            ),
        ),
    ),

    'security' => array(
        'title' => 'Security',

        'recrypt' => array(
            'title' => 'Re-Crypt All Passwords',
            'description' => 'You may find useful to be able to re-crypt all stored passwords. Old cypher will be updated so it may do hacker\'s work harder.<br />Please note this operation may take a while based on amount of total passwords.',
            'form' => array(
                'button' => array(
                    'submit' => 'Re-Crypt all passwords'
                ),
            ),
            'status' => array(
                'error' => 'Password with ID <b>:id</b> can not be re-crypted.',
                'success' => 'All passwords have been successfully re-crypted.',
            ),
        ),

        'master_password' => array(
            'title' => 'Master User & Password',
            'label' => 'Master User & Password',
            'description' => 'You can set up a master username and password here. This unique username and password will be required everytime you try to access the Cloud Password application after some period or if you close the browser. It is based on .htaccess password protection feature on your server so it\'s perfectly safe.<br />Your server requires to support .htaccess files. If you\'re not sure about it, please contact your hosting provider.',
            'form' => array(
                'button' => array(
                    'submit' => 'Setup'
                ),
            ),

            'set' => array(
                'document_title' => 'Master User & Password - Configuration',
                'article_title' => 'Set Master User & Password',

                'form' => array(
                    'label' => array(
                        'passwd_file_path' => 'Path to the file where master credentials will be stored.',
                        'passwd_area_title' => 'Area title',
                        'passwd_user' => 'Username',
                        'passwd_password' => 'Password',
                    ),
                    'value' => array(
                        'passwd_area_title' => 'Protected Area'
                    ),
                    'button' => array(
                        'save' => 'Proceed',
                    ),

                    'error' => array(
                        'required' => array(
                            'passwd_file_path' => 'Please enter the path to the file where master password will be stored.',
                            'passwd_area_title' => 'Please enter the name of the protected area displayed in authorization window.',
                            'passwd_user' => 'Please enter master username.',
                            'passwd_password' => 'Please enter the master password.',
                        ),
                    ),
                ),

                'status' => array(
                    'success' => 'Master protection credentials have been successfuly updated.',
                ),
            ),

            'clear' => array(
                'title' => 'Remove ',
                'description' => 'You can remove the master password protection bellow.',
                'form' => array(
                    'button' => array(
                        'submit' => 'Clear master password protection',
                    ),
                ),

                'status' => array(
                    'success' => 'Master protection has been cleared.',
                ),
            ),
        ),
    ),
);
