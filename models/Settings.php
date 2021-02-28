<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\Models;

use Model;
use October\Rain\Halcyon\Traits\Validation;

class Settings extends Model
{
    use Validation;

    public $implement = [
        \System\Behaviors\SettingsModel::class,
    ];
    public $settingsCode = 'azeyn_galleries_settings';
    public $settingsFields = 'fields.yaml';

    /**
     * @var string[]
     */
    public $rules = [
        'dimension' => 'required|integer|min:1',
        'responsive_dimensions' => 'required',
        'secret' => 'required'
    ];

    public function getResponsiveDimensionsAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return [
            [
                'screen_size' => 'xs',
                'dimension' => 500,
                'thumbnail_dimension' => 500,
            ], [
                'screen_size' => 'sm',
                'dimension' => 700,
                'thumbnail_dimension' => 250,
            ], [
                'screen_size' => 'md',
                'dimension' => 950,
                'thumbnail_dimension' => 250,
            ], [
                'screen_size' => 'lg',
                'dimension' => 1024,
                'thumbnail_dimension' => 300,
            ]
        ];
    }
}
