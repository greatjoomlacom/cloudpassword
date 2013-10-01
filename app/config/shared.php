<?php

return

array (
  'app' => 
  array (
    'debug' => true,
    'locale' => 'en',
    'key' => '',
    'url' => '',
    'timezone' => 'UTC',
  ),
  'database' => 
  array (
    'connections' => 
    array (
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix' => '',
      ),
    ),
  ),
  'sitename' => 'Cloud Password',
  'document' => 
  array (
    'append_position' => 'after',
  ),
  'ui' => 
  array (
    'theme' => 'redmond',
    'themes' => 
    array (
      'redmond' => 'redmond',
      'cupertino' => 'cupertino',
      'smoothness' => 'smoothness',
    ),
  ),
  'security' => 
  array (
    'master_password' => 
    array (
      'enabled' => false,
      'passwd_path' => '',
    ),
  ),
  'paginate' => 30,
  'groups' => 
  array (
    'permissions' => 
    array (
      0 => 'categories',
      1 => 'categories.view',
      2 => 'categories.edit',
      3 => 'passwords',
      4 => 'passwords.view',
      5 => 'passwords.edit',
      6 => 'users',
      7 => 'users.view',
      8 => 'users.edit',
      9 => 'groups',
      10 => 'groups.view',
      11 => 'groups.edit',
      12 => 'languages',
      13 => 'languages.view',
      14 => 'languages.edit',
      15 => 'configuration',
      16 => 'configuration.view',
      17 => 'configuration.edit',
    ),
    'protected' => 
    array (
      'Super Users' => 1000,
    ),
    'show_count_of_users' => 1,
  ),
  'datetime' => 
  array (
    'format' => 'Y-m-d H:m',
  ),
  'mode' => 'production',
  'master_password' => 
  array (
    'enabled' => false,
    'passwd_path' => '',
  ),
  'protected' => 
  array (
    'Super Users' => 1000,
  ),
);

?>