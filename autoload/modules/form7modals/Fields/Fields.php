<?php

namespace PopUpMakerForCF7\modules\form7modals\Fields;

class Fields
{
    static $form_id;
    static $registered_fields = [];

    static function init()
    {
        foreach ([
            'PopUpMakerForCF7\modules\form7modals\Fields\checkbox\checkbox',
            'PopUpMakerForCF7\modules\form7modals\Fields\colorpicker\colorpicker',
            'PopUpMakerForCF7\modules\form7modals\Fields\number\number',
            'PopUpMakerForCF7\modules\form7modals\Fields\text\text',
            'PopUpMakerForCF7\modules\form7modals\Fields\box_shadow\box_shadow'
        ] as $class_name) {
            if (method_exists($class_name, 'init_field')) {
                $class_name::init_field();
            }
        }
    }

    static function make($type, $options = [])
    {
        self::$registered_fields[$options['name']] = ['type' => $type];
        $className = '\PopUpMakerForCF7\modules\form7modals\Fields\\' . $type . '\\' . $type;
        echo $className::render($options);
    }

    static protected function validate_option($options, $require_option)
    {
        foreach ($require_option as $option_name) {
            if (!isset($options[$option_name])) {
                trigger_error('Not required option ' . $option_name);
                return false;
            }
        }
        return true;
    }
}
