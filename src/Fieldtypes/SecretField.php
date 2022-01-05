<?php

namespace Jezzdk\StatamicSecret\Fieldtypes;

use Jezzdk\StatamicSecret\StatamicSecretFacade;
use Statamic\Fields\Fieldtype;

class SecretField extends Fieldtype
{
    protected $icon = 'shield-key';

    protected $categories = ['text'];

    /**
     * @return string
     */
    public static function title()
    {
        return 'Secret';
    }

    public function augment($value)
    {
        if (empty($value)) {
            return '';
        }

        try {
            return StatamicSecretFacade::decryptString($value);
        } catch (\ErrorException $e) {
            // Oh no! We couldn't decrypt the value.
            return '<!-- Decryption error. Has the keys been overwritten? -->';
        }
    }

    /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        if (empty($data)) {
            return '';
        }

        try {
            return StatamicSecretFacade::decryptString($data);
        } catch (\ErrorException $e) {
            // Oh no! We couldn't decrypt the value.
            return '(decryption failed)';
        }
    }

    /**
     * Process the data before it gets saved.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function process($data)
    {
        if (empty(trim($data))) {
            return '';
        }

        return StatamicSecretFacade::encryptString(trim($data));
    }

    public function preProcessIndex($value)
    {
        return $this->preProcess($value);
    }

    protected function configFieldItems(): array
    {
        return [
            'input_type' => [
                'display' => 'Input type',
                'instructions' => 'Choose which input type to display to the user.',
                'type' => 'select',
                'default' => 'single',
                'options' => [
                    'single' => __('Single line'),
                    'multi' => __('Multiline'),
                ],
                'width' => 100
            ],
        ];
    }
}
