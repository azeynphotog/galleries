<?php

namespace Azeyn\Galleries\EventListeners;

use Azeyn\Galleries\Models\DecoratedImage;
use Backend\Widgets\MediaManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadedListener
{
    public function handle(MediaManager $widget, string $path, UploadedFile $file): void
    {
        $image = new DecoratedImage();
        $image->path = $path;
        $image->image = $file;
        $image->save();
    }
}
