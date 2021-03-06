<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Components;

use Azeyn\Galleries\Models\Collection as CollectionModel;
use Azeyn\Galleries\Models\DecoratedImage;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;

class Collection extends ComponentBase
{
    /**
     * @var \Azeyn\Galleries\Models\Collection
     */
    public $collection;

    /**
     * @var bool
     */
    public $infiniteScroll;

    /**
     * @var int|string
     */
    public $pageNumber;

    /**
     * @var string
     */
    public $pageParam;

    /**
     * @var string
     */
    public $indexPage;

    /**
     * @var string
     */
    public $imagePage;

    /**
     * @var string
     */
    public $imageInfoPage;

    public $images;

    public function componentDetails(): array
    {
        return [
            'name' => 'azeyn.galleries::lang.collections.view',
            'description' => '',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'slug' => [
                'title' => 'azeyn.galleries::lang.components.slug',
                'type' => 'string',
            ],
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
            'indexPage' => [
                'title' => 'azeyn.galleries::lang.collections.index_page',
                'type' => 'dropdown',
                'default' => 'galleries/collections/view',
            ],
            'imagePage' => [
                'title' => 'azeyn.galleries::lang.images.page',
                'type' => 'dropdown',
                'default' => 'galleries/image',
            ],
            'imageInfoPage' => [
                'title' => 'azeyn.galleries::lang.images.page',
                'type' => 'dropdown',
                'default' => 'galleries/image-info',
            ]
        ];
    }

    public function getIndexPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getImagePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getImageInfoPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->indexPage = $this->page['indexPage'] = $this->property('indexPage');
        $this->imagePage = $this->page['imagePage'] = $this->property('imagePage');
        $this->imageInfoPage = $this->page['imageInfoPage'] = $this->property('imageInfoPage');
        $this->infiniteScroll = $this->page['infiniteScroll'] = $this->property('infiniteScroll');
        if ($this->infiniteScroll) {
            $this->pageNumber = $this->page['page'] = 1;
        } else {
            $this->pageNumber = $this->page['page'] = $this->property('pageNumber');
        }
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        $this->collection = $this->page['collection'] = $this->loadCollection();
        if (!$this->collection) {
            $this->setStatusCode(404);
            return $this->controller->run(404);
        }

        $this->images = $this->page['images'] = $this->loadImages();

        $this->addCss('/plugins/azeyn/galleries/assets/css/app.css', 'Azeyn.Galleries');
        $this->addJs('/plugins/azeyn/galleries/assets/js/chunk-vendors.js', 'Azeyn.Galleries');
        $this->addJs('/plugins/azeyn/galleries/assets/js/jquery.mousewheel.js', 'Azeyn.Galleries');
        $this->addJs('/plugins/azeyn/galleries/assets/js/app.js', 'Azeyn.Galleries');
    }

    public function onNextPage()
    {
        $this->pageNumber = $this->page['page'] = $this->pageNumber++;
        $this->images = $this->page['images'] = $this->loadImages();
    }

    private function loadCollection()
    {
        $slug = $this->property('slug');
        return CollectionModel::where('slug', $slug)->published()->first();
    }

    private function loadImages()
    {
        $images = $this->collection->images()->list([
            'itemsPerPage' => $this->property('itemsPerPage'),
            'page' => $this->pageNumber,
        ])->get();
        if ($images->count()) {
            $images->each(function (DecoratedImage $image) {
                $image->setDefaultUrls($this->imagePage, $this->imageInfoPage, $this->controller);
            });
        }

        return json_encode($images);
    }
}
