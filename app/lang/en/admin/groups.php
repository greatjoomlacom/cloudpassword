<?php

return array(
    'document_title' => 'Groups',
    'article_title' => 'Groups',
    'article_title_icon' => 'icon-group',

    'table' => array(
        'thead' => array(
            'name' => 'Name',
        ),
        'count_of_users' => '{1}:count user |[2,4]:count users|[5,Inf]:count users',
    ),

    'status' => array(
        'error' => array(
            'group' => array(
                'exists' => 'Group <b>:name</b> already exists.',
                'not_found' => 'Group with ID <b>:id</b> not found.',
                'protected' => 'You can not manipulate with this group as it is protected.',
            ),
            'required' => array(
                'id' => '<b>Group ID</b> is required.',
                'name' => 'Field <b>Name</b> is required.',
                'permissions' => '<b>Permissions</b> are required.',
            ),
            'integer' => array(
                'id' => 'Field with <b>group ID</b> must be an integer.'
            ),
        ),
    ),

    'edit' => array(
        'document_title' => 'Edit group',
        'article_title' => 'Edit group - :name',
        'article_title_icon' => 'icon-edit',

        'status' => array(
            'success' => 'Group <b>:name</b> successfully updated.',
        ),
    ),

    'new' => array(
        'document_title' => 'New group',
        'article_title' => 'New group',
        'article_title_icon' => 'icon-plus-sign',

        'status' => array(
            'success' => 'Group <b>:name</b> has been successfully created.',
        ),
    ),

    'delete' => array(
        'status' => array(
            'success' => 'Group <b>:name</b> has been successfully deleted.',
        ),
    ),

    'form' => array(
        'heading' => array(
            'groups' => 'Groups',
        ),
        'label' => array(
            'name' => 'Name',
        ),
    ),

    'basic' => array(
        'title' => 'Basic',
    ),
    'permissions' => array(
        'title' => 'Permissions',
    ),
    'users' => array(
        'title' => 'Users'
    ),
);
