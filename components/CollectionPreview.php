<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Components;

use Azeyn\Galleries\Models\Collection;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;

class CollectionPreview extends ComponentBase
{
    /**
     * @var string
     */
    public $viewPage;

    /**
     * @var string
     */
    public $imagePage;

    /**
     * @var \Azeyn\Galleries\Models\Collection
     */
    public $collection;

    /**
     * @var \Azeyn\Galleries\Models\DecoratedImage
     */
    public $image;

    public function componentDetails(): array
    {
        return [
            'name' => 'azeyn.galleries::lang.collections.preview',
            'description' => '',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'slug' => [
                'title' => 'azeyn.galleries::lang.components.slug',
                'type' => 'dropdown',
            ],
            'viewPage' => [
                'title' => 'azeyn.galleries::lang.collections.view_page',
                'type' => 'dropdown',
                'default' => 'galleries/collections/view',
            ],
            'imagePage' => [
                'title' => 'azeyn.galleries::lang.images.page',
                'type' => 'dropdown',
                'default' => 'galleries/image',
            ]
        ];
    }

    public function getSlugOptions(): array
    {
        $collections = Collection::all();

        if (count($collections) === 0) {
            return [
                '' => 'No results'
            ];
        }

        $return = [];
        foreach ($collections as $collection) {
            $return[$collection->slug] = $collection->name;
        }

        return $return;
    }

    public function getViewPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getImagePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->viewPage = $this->page['viewPage'] = $this->property('viewPage');
        $this->imagePage = $this->page['imagePage'] = $this->property('imagePage');
        $this->collection = $this->page['collection'] = $this->loadCollection();
        $this->image = $this->page['image'] = $this->loadCoverImage();

        $this->addCss('/plugins/azeyn/galleries/assets/css/preview.css', 'Azeyn.Galleries');
        $this->addJs('/plugins/azeyn/galleries/assets/js/chunk-vendors.js', 'Azeyn.Galleries');
        $this->addJs('/plugins/azeyn/galleries/assets/js/preview.js', 'Azeyn.Galleries');
    }

    private function loadCollection()
    {
        $slug = $this->property('slug');

        $collection = Collection::where('slug', $slug)->published()->first();
        $collection->setUrl($this->viewPage, $this->controller);

        return $collection;
    }

    private function loadCoverImage()
    {
        $image = $this->collection->coverImage;
        $image->setThumbnailUrls($this->imagePage, $this->controller);
        return $image;
    }
}
