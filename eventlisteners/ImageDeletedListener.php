<?php

namespace Azeyn\Galleries\EventListeners;

use Azeyn\Galleries\Models\DecoratedImage;
use Backend\Widgets\MediaManager;

class ImageDeletedListener
{
    public function handle(MediaManager $widget, string $path): void
    {
        $image = DecoratedImage::findOrFail($path);
        $image->delete();
    }
}
