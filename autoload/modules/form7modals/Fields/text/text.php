<?php

namespace PopUpMakerForCF7\modules\form7modals\Fields\text;

use PopUpMakerForCF7\modules\form7modals\Panels\PanelsSending\PanelsSending;
use \PopUpMakerForCF7\basic\plugin;
use \PopUpMakerForCF7\modules\form7modals\Fields\Fields;
use \PopUpMakerForCF7\modules\form7modals\Fields\Field;

class text extends Field
{
    static protected $require_option = [
        'name',
        'label'
    ];

    static function render($options = [])
    {
        if (!self::validate_option($options, self::$require_option)) {
            return;
        }

        $prefix = plugin::$storage->get('PLUGIN_NAME') . Fields::$form_id;
        $id = $prefix . "-" . $options['name'];
        $value = PanelsSending::get_option(Fields::$form_id,$options['name']);

        $HTML = "
        <tr>
            <th scope='row'>
                <label for='" . $id . "'>" . $options['label'] . "</label>
            </th>
            <td>
                <input type='text' id='" . $id . "' name='" . $prefix . '[' . $options['name'] . ']' . " class='large-text code' size='70' value='" . $value . "' />
            </td>
        </tr>
        ";

        return $HTML;
    }

    static function sanitize($value,$fld){
        return strip_tags($value);
    }

    static function esc($value,$fld){
        return strip_tags($value);
    }

}
