<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Components;

use Azeyn\Galleries\Classes\ImageResizer;
use Azeyn\Galleries\Classes\TokenGenerator;
use Cache;
use Cms\Classes\ComponentBase;
use Config;
use Lang;
use Log;
use October\Rain\Exception\ApplicationException;

class ImageRenderer extends ComponentBase
{
    public function componentDetails(): array
    {
        return [
            'name' => 'azeyn.galleries::lang.images.renderer',
            'description' => '',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'hash' => [
                'title' => 'azeyn.galleries::lang.images.hash',
            ],
        ];
    }

    public function onRun()
    {
        $hash = $this->property('hash');
        if (!$hash) {
            Log::debug('Hash not present');
            $this->setStatusCode(403);
            return $this->controller->run(403);
        }

        $decoded = TokenGenerator::decode($hash);
        if (!$decoded) {
            Log::debug('Cannot decode hash');
            $this->setStatusCode(403);
            return $this->controller->run(403);
        }

        $object = json_decode($decoded, true);
        if (
            !isset($object['i'], $object['d'], $object['e']) ||
            $object['e'] < ceil((time() - Config::get('azeyn.galleries::offset')) / 60)
        ) {
            Log::debug('Object invalid: ' . implode($object));
            $this->setStatusCode(403);
            return $this->controller->run(403);
        }

        $resizer = new ImageResizer();
        $success = $resizer->resizeImage($object['i'], $object['d']);
        if (!$success) {
            throw new ApplicationException(Lang::get('azeyn.galleries::lang.images.resize_error'));
        }

        $response = $resizer->response();
        if (!$response) {
            $this->setStatusCode(404);
            return $this->controller->run(404);
        }

        return $response;
    }
}
