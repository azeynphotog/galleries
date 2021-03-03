<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Models;

use Carbon\Carbon;
use Model;
use October\Rain\Database\Traits\Sluggable;
use October\Rain\Exception\ValidationException;

class Collection extends Model
{
    use Sluggable;

    protected $table = 'azeyn_galleries_collections';

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * @var string[][]
     */
    public $belongsToMany = [
        'images' => [
            DecoratedImage::class,
            'table' => 'azeyn_galleries_collection_image',
            'key' => 'collection_id',
            'otherKey' => 'image_id'
        ]
    ];

    /**
     * @var string[]
     */
    protected $dates = ['created_at', 'updated_at', 'date', 'published_at'];

    public function scopePublished($query)
    {
        return $query->where('published_at', '<', Carbon::now());
    }

    public function getImagesSelectedAttribute(): array
    {
        $array = [];

        foreach ($this->images() as $image) {
            $item = json_decode($image->metadata, true);
            $item['title'] = $image->media()->title;
            $item['path'] = $image->media()->path;
            $array[] = $item;
        }

        return $array;
    }

    public function setImagesSelectedAttribute($value): void
    {
        $images = [];
        foreach ($value as $image) {
            $instance = DecoratedImage::where('path', $image)->first();
            if (!$instance) {
                throw new ValidationException([
                    'images_selected' => trans('azeyn.galleries::lang.images.selected_error')
                ]);
            }
            $images[] = $instance;
        }

        $this->images = $images;
    }
}
