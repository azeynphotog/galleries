<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Classes;

use Azeyn\Galleries\Models\DecoratedImage;
use Config;
use Illuminate\Filesystem\FilesystemAdapter;
use Imagick;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class ImageResizer
{
    private $adapter;
    private $path;

    public function __construct()
    {
        $adapter = new Local(Config::get('azeyn.galleries::cache.path'));
        $filesystem = new Filesystem($adapter);

        $this->adapter = new FilesystemAdapter($filesystem);
    }

    public function resizeImage(string $id, string $dimension): bool
    {
        $this->path = '';

        $cache_path = $id . '-' . $dimension;
        if ($this->adapter->exists($cache_path)) {
            $this->path = $cache_path;
            return true;
        }

        $image = DecoratedImage::find((int) $id);
        if (!$image) {
            return false;
        }

        $path = realpath(__DIR__ . '/../../../..'. media_path($image->path));
        if (!$path) {
            return false;
        }

        $processor = new Imagick($path);
        $width = $processor->getImageWidth();
        $height = $processor->getImageHeight();
        if ($width >= $height) {
            $processor->resizeImage($dimension, 0, Imagick::FILTER_TRIANGLE, 1);
        } else {
            $processor->resizeImage(0, $dimension, Imagick::FILTER_TRIANGLE, 1);
        }
        $processor->unsharpMaskImage(0.25, 0.08, 8.3, 0.045);
        $processor->setImageCompression(Imagick::COMPRESSION_JPEG);
        $processor->setImageCompressionQuality(82);
        $processor->transformImageColorspace(Imagick::COLORSPACE_SRGB);

        $this->adapter->put($cache_path, $processor->getImageBlob());
        $processor->destroy();
        $this->path = $cache_path;

        return true;
    }

    public function response()
    {
        if ($this->path === '') {
            return false;
        }

        return $this->adapter->response($this->path);
    }

    public function reset(): void
    {
        $this->path = '';
    }
}
