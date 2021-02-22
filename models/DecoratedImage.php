<?php

namespace Azeyn\Galleries\Models;

use Model as BaseModel;
use System\Models\File;

class DecoratedImage extends BaseModel
{
    protected $table = 'azeyn_galleries_images';
    public $attachOne = [
        'media' => File::class
    ];
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
}
