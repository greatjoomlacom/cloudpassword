<?php

return array(
    'document_title' => 'Passswords',
    'article_title' => ':category',
    'article_title_icon' => 'icon-list',

    'error' => array(
        'no_category_slug' => 'Category is missing. Please check the URL.',
        'missing_id' => 'Application is missing password ID.',
    ),

    'table' => array(
        'actions' => array(
            'copy_username' => 'Click to copy username',
            'copy_password' => 'Click to copy password',
            'open_link' => 'Open link in new window',
        ),
        'thead' => array(
            'title' => 'Title',
            'name' => 'Name',
            'password' => 'Password',
            'options' => 'Options',
        ),
    ),

    'admin_panel' => array(
        'button_add_new' => 'New Password',
        'button_delete_all' => 'Delete All',
    ),

    'control_buttons' => array(
        'edit' => 'Edit',
        'delete' => 'Delete',
    ),

    'delete_password' =>array(
        'success' => 'Password has been successfully deleted.'
    ),

    'copy_password' => array(
        'prompt_header' => 'Copy to clipboard (Ctrl+C or Cmd+C on Mac):',
    ),

    'form' => array(
        'label' => array(
            'category' => 'Category',
            'title' => 'Title',
            'username' => 'Username',
            'password' => 'Password',
            'url' => 'URL address',
            'note' => 'Note',
        ),
        'placeholder' => array(
            'title' => 'Enter title',
            'username' => 'Enter username',
            'password' => 'Enter password',
            'url' => 'http://www',
            'note' => 'You may enter additional notes here...',
        ),

        'button' => array(
            'save' => 'Add password',
        ),

        'error' => array(
            'required' => array(
                'id' => 'Password <strong>ID</strong> not specified.',
                'title' => 'Field <strong>Title</strong> is required.',
                'username' => 'Field <strong>Username</strong> is required.',
                'password' => 'Field <strong>Password</strong> is required.',
            ),
            'integer' => array(
                'id' => 'Password ID must be a number.',
                'category_id' => 'Category ID must be a number.'
            ),
            'url' => array(
                'url' => 'Your URL must start with prefix (http, https, ftp...).',
            ),
        ),
    ),

    'new' => array(
        'document_title' => 'New password',
        'article_title' => 'New password - :category_name',
        'article_title_icon' => 'icon-plus-sign',

        'success' => 'Password has been successfully created.',
    ),

    'edit' => array(
        'document_title' => 'Edit password',
        'article_title' => 'Edit password - :password_title',
        'article_title_icon' => 'icon-edit',

        'success' => 'Password has been successfully updated.',
    ),

    'delete_all' => array(
        'document_title' => 'Delete all passwords',
        'article_title' => 'Delete all passwords - :category_name',
        'article_title_icon' => 'icon-trash',

        'confirmation_text' => '<p>Are you sure you want to delete all passwords from <b>:category_name</b> category?</p><p>This operation is irreversible.</p>',

        'submit_button' => 'Delete all passwords',

        'error' => array(
            'missing_category_id' => 'We can not delete all passwords. Application is missing category ID.',
            'no_passwords_within' => 'There are no passwords in this category to delete.',
        ),

        'success' => 'All passwords have been successfully deleted.'
    ),

    'generator' => array(
        'form_link' => 'Password generator',
        'input_generate_title' => 'Key Length',
        'link_generate' => 'Generate',
    ),
);
