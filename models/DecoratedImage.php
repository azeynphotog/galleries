<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Models;

use Azeyn\Galleries\Classes\TokenGenerator;
use Model as BaseModel;
use October\Rain\Database\Builder;
use Storage;

class DecoratedImage extends BaseModel
{
    protected $table = 'azeyn_galleries_images';
    public $belongsToMany = [
        'models' => [
            Model::class,
            'table' => 'azeyn_galleries_model_image',
            'key' => 'image_id',
            'otherKey' => 'model_id'
        ],
        'collections' => [
            Collection::class,
            'table' => 'azeyn_galleries_collection_image',
            'key' => 'image_id',
            'otherKey' => 'collection_id'
        ],
    ];

    protected $appends = ['url', 'urls', 'thumbnail_urls'];

    /**
     * @var string
     */
    public $url = '';

    /**
     * @var array
     */
    public $urls = [];

    /**
     * @var array
     */
    public $thumbnailUrls = [];

    public function scopeList($query, array $options): Builder
    {
        if (in_array('itemsPerPage', $options, true) && $options['itemsPerPage']) {
            return $query->paginate($options['itemsPerPage'], $options['page'] ?? 1);
        }

        return $query;
    }

    public function getMediaAttribute(): string
    {
        return Storage::get($this->path);
    }

    public function getUrl($dimension, $pageName, $controller): string
    {
        $object = [
            'hash' => TokenGenerator::encode([
                'id' => $this->id,
                'dimension' => $dimension,
            ])
        ];
        return $controller->pageUrl($pageName, $object);
    }

    public function getUrlAttribute(): string
    {
        return $this->url;
    }

    public function getUrlsAttribute(): array
    {
        return $this->urls;
    }

    public function getThumbnailUrlsAttribute(): array
    {
        return $this->thumbnailUrls;
    }

    public function setDefaultUrls($renderPage, $infoPage, $controller): self
    {
        $this->thumbnailUrls = [];

        $object = [
            'hash' => TokenGenerator::encode(
                json_encode([
                    'i' => (int) $this->id,
                    'e' => (int) ceil(time() / 60 + 60 * 3),
                ])
            )
        ];
        $this->url = $controller->pageUrl($infoPage, $object);

        $dimensions = Settings::get('responsive_dimensions');
        foreach ((array) $dimensions as $dimension) {
            $object = [
                'hash' => TokenGenerator::encode(
                    json_encode([
                        'i' => (int) $this->id,
                        'd' => (int) $dimension['thumbnail_dimension'],
                        'e' => (int) ceil(time() / 60 + 5),
                    ])
                )
            ];
            $this->thumbnailUrls[$dimension['screen_size']] = $controller->pageUrl($renderPage, $object);
        }

        return $this;
    }

    public function setHighResUrls($renderPage, $infoPage, $controller): self
    {
        $this->urls = [];

        $object = [
            'hash' => TokenGenerator::encode(
                json_encode([
                    'i' => (int) $this->id,
                    'e' => (int) ceil(time() / 60 + 60 * 3),
                ])
            )
        ];
        $this->url = $controller->pageUrl($infoPage, $object);

        $dimensions = Settings::get('responsive_dimensions');
        foreach ((array) $dimensions as $dimension) {
            $object = [
                'hash' => TokenGenerator::encode(
                    json_encode([
                        'i' => (int) $this->id,
                        'd' => (int) $dimension['dimension'],
                        'e' => (int) ceil(time() / 60 + 5),
                    ])
                )
            ];
            $this->urls[$dimension['screen_size']] = $controller->pageUrl($renderPage, $object);
        }

        return $this;
    }
}
