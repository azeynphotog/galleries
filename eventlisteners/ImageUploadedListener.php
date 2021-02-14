<?php

namespace Azeyn\Galleries\EventListeners;

use Azeyn\Galleries\Models\DecoratedImage;
use October\Rain\Database\Model;
use October\Rain\Exception\ApplicationException;
use System\Models\File;
use Wikimedia\XMPReader\Reader;

class ImageUploadedListener
{
    public function handle(Model $model): void
    {
        if (!is_a($model, File::class) || !$model->isImage()) {
            return;
        }

        $image = new DecoratedImage();

        $image->path = $model->path;
        $image->image = $model;

        $content = $model->getContents();

        $xmp = new Reader();
        $offset = 0;
        for ($pos = strpos($content, '<x:xmpmeta', $offset); $pos !== false;) {
            $end = strpos($content, '</x:xmpmeta>', $pos);
            if ($end === false) {
                throw new ApplicationException('Unable to parse image file for XML data');
            }
            $offset = $end;
            $xmp_length = $pos - $end;
            $extracted = substr($content, $pos, $xmp_length + 12);
            $xmp->parseExtended($extracted);
        }

        $results = $xmp->getResults();
        $processed = $results['xmp-deprecated'] ?? [];
        if ($results['xmp-general']) {
            $processed = array_merge($processed, $results['xmp-general']);
        }
        if ($results['xmp-exif']) {
            $processed = array_merge($processed, $results['xmp-exif']);
        }
        $image->metadata = json_encode($processed, JSON_THROW_ON_ERROR);

        $image->save();
    }
}
