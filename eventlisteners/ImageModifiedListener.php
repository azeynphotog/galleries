<?php

namespace Azeyn\Galleries\EventListeners;

use Azeyn\Galleries\Models\DecoratedImage;
use Backend\Widgets\MediaManager;

class ImageModifiedListener
{
    public function handle(MediaManager $manager, string $original, string $new): void
    {
        $image = DecoratedImage::findOrFail($original);
        $image->path = $new;
        $image->save();
    }
}
