<?php

return array(
    'document_title' => 'Categories',
    'article_title' => 'Categories',
    'article_title_icon' => 'icon-folder-open-alt',

    'error' => array(
        'no_items_from_db' => 'It is not possible to load categories from database.',
        'no_categories' => 'There are no categories created yet.',
    ),

    'admin_panel' => array(
        'button_add_new' => 'New Category',
        'button_delete_all' => 'Delete All',
    ),

    'table' => array(
        'thead' => array(
            'name' => 'Title',
            'options' => 'Options',
        ),
    ),

    'control_buttons' => array(
        'edit' => 'Edit',
        'delete' => 'Delete',
    ),

    'form' => array(
        'label' => array(
            'name' => 'Name',
            'note' => 'Note',
        ),
        'placeholder' => array(
            'name' => 'Enter category name',
            'note' => 'You may enter additional notes here...',
        ),
        'error' => array(
            'required' => array(
                'id' => 'Hidden field <b>ID</b> is required.',
                'name' => 'Field <b>Name</b> is required.',
            ),
            'integer' => array(
                'id' => 'Field <b>ID</b> must be an number.'
            ),
        ),
    ),

    'new' => array(
        'document_title' => 'New category',
        'article_title' => 'New category',
        'article_title_icon' => 'icon-plus-sign',
        'success' => 'New category has been successfully added.'
    ),

    'edit' => array(
        'document_title' => 'Edit category',
        'article_title' => 'Edit category - :category_name',
        'article_title_icon' => 'icon-edit',
        'success' => 'Category has been successfully updated.'
    ),

    'delete' => array(
        'item_not_found' => 'It is not possible to delete this category because it does not exists.',
        'success' => 'Category has been successfully deleted.',
    ),

    'delete_all' => array(
        'document_title' => 'Delete all categories',
        'article_title' => 'Delete all categories',
        'article_title_icon' => 'icon-trash',

        'confirmation_text' => '<p>Are you sure you want to delete all categories? This will also <b>remove all password</b> attached to all categories!</p><p>This operation is irreversible!</p>',

        'submit_button' => 'Delete all categories',

        'error' => 'It is not possible to delete all categories from database table.',

        'success' => 'All categories with all children passwords have been successfully deleted.',
    ),
);
