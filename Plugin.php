<?php

namespace Azeyn\Galleries;

use Azeyn\Galleries\EventListeners\ImageListener;
use Backend;
use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name'        => 'azeyn.galleries::lang.plugin.name',
            'description' => 'azeyn.galleries::lang.plugin.description',
            'author'      => 'Azeyn',
            'icon'        => 'icon-file-image-o',
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'azeyn.galleries.access_collections' => [
                'tab'   => 'azeyn.galleries::lang.plugin.tab',
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

    public function registerNavigation()
    {
        return [
            'galleries' => [
                'label'       => 'azeyn.galleries::lang.collections.menu_label',
                'url'         => Backend::url('azeyn/galleries/collections'),
                'icon'        => 'icon-file-image-o',
                'permissions' => ['azeyn.galleries.*'],
                'order'       => 500,

                'sideMenu' => [
                    'collections' => [
                        'label' => 'azeyn.galleries::lang.collections.menu_label',
                        'icon'        => 'icon-file-image-o',
                        'url'         => Backend::url('azeyn/galleries/collections'),
                        'permissions' => ['azeyn.galleries.access_collections']
                    ]
                ]
            ]
        ];
    }

    public function boot(): void
    {
        Event::subscribe(new ImageListener);
    }
}
