<?php
/*
 * Copyright (c) 2021 Azeyn
 *
 * The galleries plugin is licensed under the PolyForm Noncommercial License 1.0.0
 */

namespace Azeyn\Galleries\FormWidgets;

use Azeyn\Galleries\Models\DecoratedImage;
use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use Media\Classes\MediaLibrary;

class MediaPicker extends FormWidgetBase
{
    /**
     * @var string Prompt to display if no record is selected.
     */
    public $prompt = 'backend::lang.mediafinder.default_prompt';

    /**
     * @inheritdoc
     */
    protected $defaultAlias = 'media';

    public function init(): void
    {
        $this->fillFromConfig([
            'prompt'
        ]);
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('mediapicker');
    }

    public function prepareVars(): void
    {
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['field'] = $this->formField;
        $this->vars['prompt'] = str_replace('%s', '<i class="icon-folder"></i>', trans($this->prompt));
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        if ($this->formField->disabled || $this->formField->hidden) {
            return FormField::NO_SAVE_DATA;
        }

        $return = [];
        foreach ((array) $value as $path) {
            $return[] = DecoratedImage::where('path', $path)->firstOrFail();
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function getLoadValue()
    {
        if ($this->formField->value !== null) {
            if (empty($this->formField->value)) {
                return [];
            }

            $return = [];
            foreach ((array) $this->formField->value as $id) {
                $image = DecoratedImage::find($id);
                $directory = MediaLibrary::instance()->listFolderContents(dirname($image->path), 'title', null, true);
                foreach ($directory as $file) {
                    if ($file->path === $image->path) {
                        $return[] = $file;
                        break;
                    }
                }
            }
            return $return;
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets(): void
    {
        $this->addJs('js/jquery.tablednd.min.js', 'core');
        $this->addJs('js/mediapicker.js', 'core');
        $this->addCss('css/mediapicker.css', 'core');
    }
}
