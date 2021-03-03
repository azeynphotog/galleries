<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Models;

use Model as BaseModel;
use October\Rain\Database\Traits\Sluggable;

class Model extends BaseModel
{
    use Sluggable;

    /**
     * @var string
     */
    protected $table = 'azeyn_galleries_models';

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
            'table' => 'azeyn_galleries_model_image',
            'key' => 'model_id',
            'otherKey' => 'image_id'
        ]
    ];
}
