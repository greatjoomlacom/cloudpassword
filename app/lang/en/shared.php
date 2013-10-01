<?php

return array(
    'document_title_suffix' => 'Cloud Password',
    'document_title_index' => 'Cloud Password',

    'error' => array(
        'uknown' => 'Uknown application error.',
        'invalid_token' => 'Invalid token.',
        'login_first' => 'Please login first.',
        'not_auth' => 'You are not authorized to perform this action.',

        'db' => array(
            'no_item' => 'It is not possible to load an item from database.',
            'delete' => 'It is not possible to delete an item from database.',
            'insert' => 'It is not possible to insert an item to database.',
            'edit' => 'It is not possible to edit an item in database.',
            'update' => 'It is not possible to update an item in database.',
            'create' => 'It is not possible to create an item in database.',
        ),

        'edit_foreign' => 'You are not authorized to edit this item.',

        'filesystem' => array(
            'is_dir' => 'Following directory is not actually a directory:<br /> <i>:path</i>',
            'file_exists' => 'Following file does not exists:<br /> <i>:path</i>',
            'delete_dir' => 'It is not possible to delete following directory:<br /> <i>:path</i><br />You should check file permission on the directory.',
            'delete_file' => 'It is not possible to delete following file:<br /> <i>:path</i><br />You should check file permission on the file.',
            'write_dir' => 'It is not possible to create following directory:<br /> <i>:path</i><br />You should check file permission on the directory.',
            'write_file' => 'It is not possible to write to following file:<br /> <i>:path</i><br />You should check file permission on the file.',
            'copy_file' => 'It is not possible to copy following file:<br /> <i>:path</i><br />You should check file permission on the file.',
        ),

        'status' => array(
            'http' => array(
                '404' => 'Page not found.<br />:url',
            ),

        ),

        'no_data_to_display' => 'There are no items to display.',
    ),

    'admin_table' => array(
        'thead' => array(
            'options' => 'Options',
        ),
        'action' => array(
            'edit' => 'Edit',
            'delete' => 'Delete',
        ),
    ),

    'script' => array(
        'confirmation' => array(
            'delete_one' => 'Are you sure you want to delete this item?',
            'delete_all' => 'Are you sure you want to delete all items?',
        ),
    ),

    'control_buttons' => array(
        'edit' => 'Edit',
        'delete' => 'Remove',
        'default' => 'Make default',
        'add_new' => 'Add new',
    ),

    'form' => array (
        'required_mark' => '*',
        'required_mark_footer' => '* required fields',

        'button' => array(
            'cancel' => 'Cancel',
            'reset' => 'Reset',
            'edit' => 'Edit',
            'update' => 'Update',
            'back' => 'Back',
            'save' => 'Save',
            'add' => 'Add',
        ),

        'error' => array(
            'required' => array(
                'filter' => 'Some filter string is required.'
            ),
        ),

        'filter' => array(
            'placeholder' => array(
                'title' => 'search results'
            ),
            'advanced' => array(
                'title' => 'Filter',
                'button' => array(
                    'submit' => 'Filter',
                    'submit_title' => 'Filter results',
                    'clear' => 'Clear',
                ),
                'status_active' => 'active',
            ),
        ),

        'select' => '--- Select ---'
    ),

    'label' => array(
        'default' => 'default',
    ),

    'pagination' => array(
        'filtered' => 'Filtered: :filtered results from :total items.'
    ),

    'permissions' => array(
        'group' => array(
            'description' => 'This group is allowed to:',
            'action' => array(
                'superuser' => '<b>Authorized to everything.</b>',
                'passwords' => 'Passwords - manage own list.',
                'passwords_view' => 'Passwords - view own list.',
                'passwords_edit' => 'Passwords - edit own list.',
                'categories' => 'Categories - manage.',
                'categories_view' => 'Categories - view.',
                'categories_edit' => 'Categories - edit.',
                'users' => 'User accounts - manage.',
                'users_view' => 'User accounts - view.',
                'users_edit' => 'User accounts - edit.',
                'groups' => 'Groups - manage.',
                'groups_view' => 'Groups - view.',
                'groups_edit' => 'Groups - edit.',
                'languages' => 'Languages - manage.',
                'languages_view' => 'Languages - view.',
                'languages_edit' => 'Languages - edit.',
                'configuration' => 'Configuration - manage.',
                'configuration_view' => 'Configuration - view.',
                'configuration_edit' => 'Configuration - edit.',
            ),
        ),
    ),
);
