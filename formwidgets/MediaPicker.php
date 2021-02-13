<?php

namespace Azeyn\Galleries\FormWidgets;

use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;

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
        $value = $this->getLoadValue();

        $this->vars['value'] = empty($value) ? [] : $value;
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

        return $value;
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets(): void
    {
        $this->addJs('js/mediapicker.js', 'core');
        $this->addCss('css/mediapicker.css', 'core');
    }
}
