<?php

namespace PopUpMakerForCF7\modules\form7modals\Fields\colorpicker;

use PopUpMakerForCF7\modules\form7modals\Panels\PanelsSending\PanelsSending;
use \PopUpMakerForCF7\basic\plugin;
use \PopUpMakerForCF7\modules\form7modals\Fields\Fields;
use \PopUpMakerForCF7\modules\form7modals\Fields\Field;

class colorpicker extends Field
{
    static protected $require_option = [
        'name',
        'label'
    ];

    static function init_field()
    {
        add_action(plugin::$storage->get('PLUGIN_NAME') . '_admin_assets', [__CLASS__, 'assets']);
    }

    static function render($options = [])
    {
        if (!self::validate_option($options, self::$require_option)) {
            return;
        }

        $prefix = plugin::$storage->get('PLUGIN_NAME') . Fields::$form_id;
        $id = $prefix . "-" . $options['name'];
        $value = PanelsSending::get_option(Fields::$form_id,$options['name']);
        if(empty($value)){ $value = $options['default']; }

        $HTML = "
        <tr>
            <th scope='row'>
                <label for='" . $id . "'>" . $options['label'] . "</label>
            </th>
            <td>
                <input data-coloris type='text' id='" . $id . "' name='" . $prefix . '[' . $options['name'] . ']' . " class='large-text code' size='20' value='" . $value . "' />
            </td>
        </tr>
        ";

        return $HTML;
    }

    static function assets()
    {
        wp_enqueue_style('colorismain', plugin::$storage->get('PLUGIN_URL') . '/autoload/modules/form7modals/Fields/colorpicker/js/libs/colorismain/dist/coloris.min.css');
        wp_enqueue_script('colorismain', plugin::$storage->get('PLUGIN_URL') . '/autoload/modules/form7modals/Fields/colorpicker/js/libs/colorismain/dist/coloris.min.js', array(), null, true);
    }

    static function sanitize($value,$fld){
        return sanitize_hex_color($value);
    }
    
    static function esc($value,$fld){
        return sanitize_hex_color($value);
    }

}
