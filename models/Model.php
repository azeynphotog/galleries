<?php

namespace Azeyn\Galleries\Models;

use Model as BaseModel;
use October\Rain\Database\Traits\Sluggable;

class Model extends BaseModel
{
    use Sluggable;

    protected $table = 'azeyn_galleries_models';

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    public $belongsToMany = [
        'images' => [
            DecoratedImage::class,
            'table' => 'azeyn_galleries_model_image',
            'key' => 'model_id',
            'otherKey' => 'image_id'
        ]
    ];
}
