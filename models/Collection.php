<?php

namespace Azeyn\Galleries\Models;

use Carbon\Carbon;
use Model;
use October\Rain\Database\Traits\Sluggable;

class Collection extends Model
{
    use Sluggable;

    protected $table = 'azeyn_galleries_collections';

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    public $belongsToMany = [
        'images' => [
            DecoratedImage::class,
            'table' => 'azeyn_galleries_collection_image',
            'key' => 'collection_id',
            'otherKey' => 'image_id'
        ]
    ];
    protected $dates = ['created_at', 'updated_at', 'date', 'published_at'];

    public function scopePublished($query)
    {
        return $query->where('published_at', '<', Carbon::now());
    }
}
