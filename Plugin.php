<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries;

use Azeyn\Galleries\Components\Collection;
use Azeyn\Galleries\Components\CollectionsIndex;
use Azeyn\Galleries\Components\Image;
use Azeyn\Galleries\Components\ImageRenderer;
use Azeyn\Galleries\EventListeners\ImageListener;
use Backend;
use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name' => 'azeyn.galleries::lang.plugin.name',
            'description' => 'azeyn.galleries::lang.plugin.description',
            'author' => 'Azeyn',
            'icon' => 'icon-file-image-o',
        ];
    }

    public function registerComponents(): array
    {
        return [
            Collection::class => 'collection',
            CollectionsIndex::class => 'collectionsIndex',
            Image::class => 'image',
            ImageRenderer::class => 'imageRenderer',
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'azeyn.galleries.access_collections' => [
                'tab' => 'azeyn.galleries::lang.plugin.tab',
                'label' => 'azeyn.galleries::lang.plugin.access_collections'
            ],
            'azeyn.galleries.access_settings' => [
                'tab'   => 'azeyn.galleries::lang.plugin.tab',
                'label' => 'azeyn.galleries::lang.plugin.access_settings'
            ],
        ];
    }

    public function registerFormWidgets(): array
    {
        return [
            \Azeyn\Galleries\FormWidgets\MediaPicker::class => 'mediapicker',
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'galleries' => [
                'label' => 'azeyn.galleries::lang.collections.menu_label',
                'url' => Backend::url('azeyn/galleries/collections'),
                'icon' => 'icon-file-image-o',
                'permissions' => ['azeyn.galleries.*'],
                'order' => 500,

                'sideMenu' => [
                    'collections' => [
                        'label' => 'azeyn.galleries::lang.collections.menu_label',
                        'icon' => 'icon-file-image-o',
                        'url' => Backend::url('azeyn/galleries/collections'),
                        'permissions' => ['azeyn.galleries.access_collections']
                    ],
                    'models' => [
                        'label' => 'azeyn.galleries::lang.models.menu_label',
                        'icon' => 'icon-user',
                        'url' => Backend::url('azeyn/galleries/models'),
                        'permissions' => ['azeyn.galleries.access_collections']
                    ]
                ]
            ]
        ];
    }

    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label' => 'azeyn.galleries::lang.plugin.tab',
                'description' => 'azeyn.galleries::lang.plugin.access_settings',
                'category' => 'azeyn.galleries::lang.plugin.tab',
                'icon' => 'icon-file-image-o',
                'class' => \Azeyn\Galleries\Models\Settings::class,
                'order' => 500,
                'keywords' => 'media gallery',
                'permissions' => ['azeyn.galleries.access_settings'],
            ],
        ];
    }

    public function boot(): void
    {
        Event::subscribe(new ImageListener);
    }
}
