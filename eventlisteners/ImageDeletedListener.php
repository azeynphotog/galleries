<?php

namespace Azeyn\Galleries\EventListeners;

use Azeyn\Galleries\Models\DecoratedImage;
use October\Rain\Database\Model;
use System\Models\File;

class ImageDeletedListener
{
    public function handle(Model $model): void
    {
        if (!is_a($model, File::class) || !$model->isImage()) {
            return;
        }

        $image = DecoratedImage::find($model->path);
        if (!$image) {
            return;
        }

        $image->delete();
    }
}
