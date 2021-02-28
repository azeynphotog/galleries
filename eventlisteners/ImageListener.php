<?php

namespace Azeyn\Galleries\EventListeners;

use Azeyn\Galleries\Models\DecoratedImage;
use Backend\Widgets\MediaManager;
use Illuminate\Http\UploadedFile;
use Log;
use October\Rain\Exception\ApplicationException;
use Wikimedia\XMPReader\Reader;

class ImageListener
{
    public function uploaded(MediaManager $media, string $path, UploadedFile $file): void
    {
        Log::debug('Media file ' . $path . ' uploaded');

        $image = new DecoratedImage();
        $image->path = $path;

        $content = $file->get();

        $xmp = new Reader();
        $offset = 0;
        while ($pos = strpos($content, '<x:xmpmeta', $offset)) {
            $end = strpos($content, '</x:xmpmeta>', $pos);
            if ($end === false) {
                throw new ApplicationException('Unable to parse image file for XML data');
            }
            $xmp_length = $end - $pos;
            $extracted = substr($content, $pos, $xmp_length + 12);
            $xmp->parse($extracted);
            $xmp->parseExtended($extracted);
            $offset = $end;
        }

        $results = $xmp->getResults();
        $processed = $results['xmp-deprecated'] ?? [];
        if (isset($results['xmp-general'])) {
            $processed = array_merge($processed, $results['xmp-general']);
        }
        if (isset($results['xmp-exif'])) {
            $processed = array_merge($processed, $results['xmp-exif']);
        }

        $image->metadata = json_encode($processed);

        $image->save();
    }

    public function modified(MediaManager $manager, string $original, string $new): void
    {
        $image = DecoratedImage::where('path', $original)->first();
        if (!$image) {
            return;
        }

        $image->path = $new;
        $image->save();
    }

    public function deleted(MediaManager $media, string $path): void
    {
        Log::debug('Media file ' . $path . ' deleted');

        $image = DecoratedImage::where('path', $path)->first();
        if (!$image) {
            return;
        }

        $image->delete();
    }

    public function subscribe($events)
    {
        $events->listen('media.file.delete', '\Azeyn\Galleries\EventListeners\ImageListener@deleted');
        $events->listen('media.file.move', '\Azeyn\Galleries\EventListeners\ImageListener@modified');
        $events->listen('media.file.rename', '\Azeyn\Galleries\EventListeners\ImageListener@modified');
        $events->listen('media.file.upload', '\Azeyn\Galleries\EventListeners\ImageListener@uploaded');
    }
}
