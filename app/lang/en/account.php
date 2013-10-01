<?php

return array(
    'document_title' => 'Account',
    'article_title' => 'Account Details',

    'error' => array(
        'credentials' => array(
            'missing_all' => 'Don\'t forget to fill your login credentials.',
            'missing_loginname' => 'Don\'t forget to enter your email.',
            'missing_password' => 'Don\'t forget to enter your password.',
            'wrong_password' => 'Entered password is wrong. Please try again.',
            'not_activated' => 'This user account does not seems to be activated.',
            'not_found' => 'User with specified credentials has not been found. Please try it again.',
        )
    ),

    'form' => array(
        'label' => array(
            'password' => 'Password',
            'password2' => 'Password again',
        ),
    ),

    'login' => array(
        'form' => array(
            'email' => 'Email',
            'password' => 'Password',
            'submit' => 'Log In',
        ),
    ),

    'logout' => array(
        'form' => array(
            'button' => 'Logout'
        ),
    ),

    'info' => array(
        'logged_as' => 'You are logged in as <b>:first_name :last_name.</b>',
    ),

    'edit' => array(
        'form' => array(
            'label' => array(
                'email' => 'Email',
                'password' => 'Password',
                'password2' => 'Password again',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'language' => 'Language',
            ),
        ),

        'status' => array(
            'success' => 'Account details successfully updated.',

            'error' => array(
                'user' => array(
                    'exists' => 'User with <b>:email</b> email address already exists.',
                    'not_found' => 'User not found.',
                ),
                'required' => array(
                    'id' => '<b>User ID</b> is required.',
                    'email' => 'Field <b>Email</b> is required.',
                    'password' => 'Field <b>Password</b> is required.',
                    'password2' => 'Field <b>Password again</b> is required.',
                    'details' => array(
                        'first_name' => 'Field with <b>First Name</b> is required.',
                        'last_name' => 'Field with <b>Last Name</b> is required.',
                        'language' => 'Field with <b>Language</b> is required.',
                    ),
                ),
                'email' => array(
                    'email' => 'Field <b>Email</b> must be an email.'
                ),
                'integer' => array(
                    'id' => 'Field with <b>user ID</b> must be an integer.'
                ),
            ),
        ),
    ),
);
