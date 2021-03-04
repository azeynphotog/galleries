<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Components;

use Azeyn\Galleries\Classes\TokenGenerator;
use Azeyn\Galleries\Models\DecoratedImage;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Config;
use Response;

class Image extends ComponentBase
{
    /**
     * @var string
     */
    public $imagePage;

    /**
     * @var \Azeyn\Galleries\Models\DecoratedImage
     */
    public $image;

    public function componentDetails(): array
    {
        return [
            'name' => 'azeyn.galleries::lang.images.image',
            'description' => '',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'hash' => [
                'title' => 'azeyn.galleries::lang.images.hash',
            ],
            'imagePage' => [
                'title' => 'azeyn.galleries::lang.images.page',
                'type' => 'dropdown',
                'default' => 'galleries/image',
            ],
        ];
    }

    public function getImagePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->imagePage = $this->page['imagePage'] = $this->property('imagePage');

        $hash = $this->property('hash');
        if (!$hash) {
            Log::debug('Hash not present');
            $this->setStatusCode(403);
            return $this->controller->run(403);
        }

        $decoded = TokenGenerator::decode($hash);
        if (!$decoded) {
            Log::debug('Cannot decode hash');
            $this->setStatusCode(403);
            return $this->controller->run(403);
        }

        $object = json_decode($decoded, true);
        if (
            !isset($object['i'], $object['e']) ||
            $object['e'] < ceil((time() - Config::get('azeyn.galleries::offset')) / 60)
        ) {
            Log::debug('Object invalid: ' . $object);
            $this->setStatusCode(403);
            return $this->controller->run(403);
        }

        $this->image = $this->page['image'] = DecoratedImage::find($object['i']);
        $this->image->setHighResUrls($this->imagePage, $this->controller->getPage()->getBaseFileName(), $this->controller);
        return Response::json($this->image);
    }
}
