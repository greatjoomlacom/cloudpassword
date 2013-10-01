<?php

return array(
    'document_title' => 'Users',
    'article_title' => 'Users',
    'article_title_icon' => 'icon-user',

    'table' => array(
        'thead' => array(
            'name' => 'Name',
            'email' => 'Email',
            'dateinfo' => 'Created/Updated',
            'language' => 'Language',
            'groups' => 'Groups',
        ),
    ),

    'filter' => array(
        'label' => array(
            'group' => 'Group',
            'language' => 'Language',
        ),
    ),

    'status' => array(
        'error' => array(
            'user' => array(
                'exists' => 'User <b>:email</b> already exists.',
                'not_found' => 'User <b>:email</b> not found.',
                'delete_own' => 'You can not delete your own account.',
                'delete_super_user' => 'You can not delete super user account.',
            ),
            'passwords_do_not_match' => 'Passwords do not match.',
            'group' => array(
                'not_found' => 'Group with ID <b>:id</b> not found.',
            ),
            'required' => array(
                'id' => '<b>User ID</b> is required.',
                'password' => 'Field <b>Password</b> is required.',
                'password2' => 'Field <b>Password again</b> is required.',
                'email' => 'Field <b>Email</b> is required.',
                'details' => 'Details are required.',
                'groups' => 'You have to assign the user to some group.',
            ),
            'email' => array(
                'email' => 'Field <b>Email</b> must be an email.'
            ),
            'integer' => array(
                'id' => 'Field with <b>user ID</b> must be an integer.'
            ),
        ),
    ),

    'edit' => array(
        'document_title' => 'Edit user',
        'article_title' => 'Edit user - :name',
        'article_title_icon' => 'icon-edit',

        'status' => array(
            'success' => 'User details successfully updated.',
        ),
    ),

    'new' => array(
        'document_title' => 'New user',
        'article_title' => 'New user',
        'article_title_icon' => 'icon-plus-sign',

        'status' => array(
            'success' => 'User <b>:name</b> has been successfully created.',
        ),
    ),

    'delete' => array(
        'status' => array(
            'success' => 'User successfully deleted.',
        ),
    ),

    'basic' => array(
        'title' => 'Basic',
    ),

    'groups' => array(
        'title' => 'Groups',
    ),

    'form' => array(
        'heading' => array(
            'groups' => 'Groups',
        ),
        'label' => array(
            'group' => 'Group',
        ),

        'group' => array(
            'description' => 'This group is allowed to:',
            'actions' => array(
                'users' => 'Manage user accounts.',
                'users_view' => 'View user accounts.',
                'users_edit' => 'Edit user accounts.',
                'languages' => 'Manage languages.',
                'languages_view' => 'View languages.',
                'languages_edit' => 'Edit languages.',
                'configuration' => 'Manage configuration.',
                'configuration_view' => 'View configuration.',
                'configuration_edit' => 'Edit configuration.',
            ),
        ),
    ),
);
