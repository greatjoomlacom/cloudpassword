<?php

return

array (
  'driver' => 'eloquent',
  'hasher' => 'native',
  'cookie' => 
  array (
    'key' => 'cartalyst_sentry',
  ),
  'groups' => 
  array (
    'model' => 'Group',
  ),
  'users' => 
  array (
    'model' => 'User',
    'login_attribute' => 'email',
  ),
  'throttling' => 
  array (
    'enabled' => true,
    'model' => 'Cartalyst\\Sentry\\Throttling\\Eloquent\\Throttle',
    'attempt_limit' => 5,
    'suspension_time' => 15,
  ),
);

?>