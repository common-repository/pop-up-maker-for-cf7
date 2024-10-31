<?php

namespace PopUpMakerForCF7\modules\form7modals\Fields;

class Field
{
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
