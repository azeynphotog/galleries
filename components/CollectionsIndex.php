<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;

class CollectionsIndex extends ComponentBase
{
    public function componentDetails(): array
    {
        return [
            'name' => 'azeyn.galleries::lang.collections.index',
            'description' => '',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'itemsPerPage' => [
                'title' => 'azeyn.galleries::lang.components.items_per_page',
                'description' => 'azeyn.galleries::lang.components.items_per_page_description',
                'type' => 'string',
                'default' => 10,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'azeyn.galleries::lang.components.items_per_page_message',
            ],
            'page' => [
                'title' => 'azeyn.galleries::lang.components.page',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'azeyn.galleries::lang.components.page_message',
            ],
            'infiniteScroll' => [
                'title' => 'azeyn.galleries::lang.components.infinite_scroll',
                'description' => 'azeyn.galleries::lang.components.infinite_scroll_description',
                'type' => 'checkbox',
            ],
            'viewPage' => [
                'title' => 'azeyn.galleries::lang.collections.view_page',
                'type' => 'dropdown',
                'default' => 'galleries/collections/view',
            ],
        ];
    }

    public function getViewPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
}
