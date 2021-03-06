<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

return [
    'plugin' => [
        'name' => 'Gallery',
        'description' => '',
        'tab' => 'Galleries',
        'access_collections' => 'Manage galleries',
        'access_settings' => 'Manage gallery settings',
    ],
    'collections' => [
        'create_collection' => 'Gallery',
        'delete_confirm' => 'Delete the gallery?',
        'description' => 'Description',
        'list_title' => 'Manage galleries',
        'index' => 'Galleries listing',
        'index_page' => 'Galleries listing page',
        'menu_label' => 'Galleries',
        'name' => 'Name',
        'new_collection' => 'New gallery',
        'published_at' => 'Published',
        'return_to_list' => 'Return to galleries list',
        'slug' => 'Slug',
        'view' => 'Gallery',
        'view_page' => 'Gallery page',
    ],
    'components' => [
        'infinite_scroll' => 'Infinite scroll',
        'infinite_scroll_description' => 'Enable automatic loading of content on scroll to bottom',
        'items_per_page' => 'Items per page',
        'items_per_page_description' => 'The number of items to show on each page',
        'items_per_page_message' => 'The items per page property can only contain numeric characters',
        'page' => 'Page',
        'page_message' => 'The page property can only contain numeric characters',
        'slug' => 'Slug',
    ],
    'images' => [
        'dimension' => 'Dimension',
        'dimension_description' => 'The default maximum dimension of rendered photos',
        'dimension_message' => 'The dimension property can only contain numeric characters',
        'filename' => 'File name',
        'hash' => 'Hash',
        'no_images' => 'No images have been selected',
        'page' => 'Image page',
        'renderer' => 'Image renderer',
        'resize_error' => 'Unable to resize image',
        'responsive_dimensions' => 'Responsive dimensions',
        'responsive_dimensions_description' => 'Dimension for photos to show under different devices',
        'screen' => 'Screen',
        'screen_lg' => 'Large',
        'screen_md' => 'Medium',
        'screen_sm' => 'Small',
        'screen_xl' => 'Extra large',
        'screen_xs' => 'Extra small',
        'select' => 'Select images',
        'thumbnail_dimension' => 'Thumbnail dimension',
        'thumbnail_dimension_description' => 'Dimension of the thumbnail at its longest side',
        'view' => 'Image info'
    ],
    'models' => [
        'create_model' => 'Model',
        'delete_confirm' => 'Delete the model?',
        'list_title' => 'Manage models',
        'menu_label' => 'Models',
        'name' => 'Name',
        'new_model' => 'New model',
        'slug' => 'Slug'
    ],
    'settings' => [
        'secret' => 'Secret',
        'secret_description' => '',
    ],
];
