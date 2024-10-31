<?php

namespace PopUpMakerForCF7\modules\form7modals\Fields\checkbox;

use PopUpMakerForCF7\modules\form7modals\Panels\PanelsSending\PanelsSending;
use \PopUpMakerForCF7\basic\plugin;
use \PopUpMakerForCF7\modules\form7modals\Fields\Fields;
use \PopUpMakerForCF7\modules\form7modals\Fields\Field;

class checkbox extends Field
{
    static protected $require_option = [
        'name',
        'label',
        'options'
    ];

    static function render($options = [])
    {
        if (!self::validate_option($options, self::$require_option)) {
            return;
        }

        $prefix = plugin::$storage->get('PLUGIN_NAME'). Fields::$form_id;
        $id = $prefix . "-" . $options['name'];
        $value = PanelsSending::get_option(Fields::$form_id,$options['name']);
        $collspan = '';
        if(isset($options['colspan'])){ $collspan = 'colspan="'.$options['colspan'].'"'; }

        $HTML = "
        <tr>
            <td $collspan>";
        foreach ($options['options'] as $name => $label) {
            $checked = (isset($value) && isset($value[$name]) && $value[$name] === 'on') ? 'checked' : '';
            $HTML .= '
                        <p>
                            <label for="' . $id . '-' . $name . '">
                                <input type="checkbox" id="' . $id . '-' . $name . '" name="' . $prefix . '[' . $options['name'] . ']' . '[' . $name . ']"' . $checked . ' />
                                ' . __($label, 'pop-up-maker-for-cf7') . '
                            </label>
                        </p>';
        }

        $HTML .=  "
            </td>
        </tr>
        ";

        return $HTML;
    }
    
    static function sanitize($value,$fld){
        if(!isset($fld['options'])){ return []; }
        if(empty($fld['options'])){ return []; }
        if(!is_array($value)){ return []; }

        $return = [];
        foreach ($fld['options'] as $option_name => $option_label){
            if(!isset($value[$option_name])) continue;
            if($value[$option_name] === 'on'){ $return[$option_name] = 'on'; }
        }

        return $return;
    }

    static function esc($value,$fld){
        if(!isset($fld['options'])){ return []; }
        if(empty($fld['options'])){ return []; }
        if(!is_array($value)){ return []; }

        $return = [];
        foreach ($fld['options'] as $option_name => $option_label){
            if(!isset($value[$option_name])) continue;
            if($value[$option_name] === 'on'){ $return[$option_name] = 'on'; }
        }
        return $return;
    }
}
