<?php

namespace Azeyn\Galleries\EventListeners;

use Azeyn\Galleries\Models\DecoratedImage;
use October\Rain\Database\Model;
use System\Models\File;

class ImageUploadedListener
{
    public function handle(Model $model): void
    {
        if (!is_a($model, File::class)) {
            return;
        }

        $image = new DecoratedImage();
        $image->path = $model->path;
        $image->image = $model;
        $image->save();
    }
}
