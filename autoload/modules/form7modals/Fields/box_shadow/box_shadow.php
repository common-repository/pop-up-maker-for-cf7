<?php

namespace PopUpMakerForCF7\modules\form7modals\Fields\box_shadow;

use PopUpMakerForCF7\modules\form7modals\Panels\PanelsSending\PanelsSending;
use \PopUpMakerForCF7\basic\plugin;
use \PopUpMakerForCF7\modules\form7modals\Fields\Fields;
use \PopUpMakerForCF7\modules\form7modals\Fields\Field;


class box_shadow extends Field
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

        $offset_x = (isset($value['offset_x']) && !empty($value['offset_x'])) ? $value['offset_x'] : '0';
        $offset_y = (isset($value['offset_y']) && !empty($value['offset_y'])) ? $value['offset_y'] : '0';
        $blur_radius = (isset($value['blur_radius']) && !empty($value['blur_radius'])) ? $value['blur_radius'] : '0';
        $color = (isset($value['color']) && !empty($value['color'])) ? $value['color'] : '#000';


        $HTML = "
        <tr class='box_shadow_picker'>
            <th scope='row'>
                <label for='" . $id . "'>" . $options['label'] . "</label>
            </th>
            <td>
                <div class='box_shadow_wrapper' style='display: flex;'>
                    <span style='display: flex;flex-direction: column;'>
                        <small>offset_x</small>
                        <input type='number' min='-100' max='100' id='" . $id . "' name='" . $prefix . '[' . $options['name'] . '][offset_x]' . " class='large-text code' size='10' value='" . $offset_x . "' />
                    </span>
                    <span style='display: flex;flex-direction: column;'>
                        <small>offset_y</small>
                        <input type='number' min='-100' max='100' id='" . $id . "' name='" . $prefix . '[' . $options['name'] . '][offset_y]' . " class='large-text code' size='10' value='" . $offset_y . "' />
                    </span>
                    <span style='display: flex;flex-direction: column;'>
                        <small>blur_radius</small>
                        <input type='number' min='-100' max='100' id='" . $id . "' name='" . $prefix . '[' . $options['name'] . '][blur_radius]' . " class='large-text code' size='10' value='" . $blur_radius . "' />
                    </span>
                    <span style='display: flex;flex-direction: column;'>
                        <small>color</small>
                        <input type='text' data-coloris  id='" . $id . "' name='" . $prefix . '[' . $options['name'] . '][color]' . " class='large-text code' size='10' value='" . $color . "' />
                    </span>
                </div>

                <div class='box_shadow_exemple'
                style='
                    box-shadow: " . $offset_x . "px " . $offset_y . "px " . $blur_radius . "px " . $color . ";
                    height: 30px; width: 100%; margin-top: 16px;background-color: #fff;
                '
                ></div>

            </td>
        </tr>
        ";

        return $HTML;
    }

    static function assets()
    {
        wp_enqueue_style('colorismain', plugin::$storage->get('PLUGIN_URL') . '/autoload/modules/form7modals/Fields/colorpicker/js/libs/colorismain/dist/coloris.min.css');
        wp_enqueue_script('colorismain', plugin::$storage->get('PLUGIN_URL') . '/autoload/modules/form7modals/Fields/colorpicker/js/libs/colorismain/dist/coloris.min.js', array(), null, true);
        wp_enqueue_script('box_shadow', plugin::$storage->get('PLUGIN_URL') . '/autoload/modules/form7modals/Fields/box_shadow/js/box_shadow.js', array(), null, true);
    }

    static function sanitize($value,$fld){
        $return = [];
        $return['offset_x'] = (is_numeric($value['offset_x']))?$value['offset_x']:'';
        $return['offset_y'] = (is_numeric($value['offset_y']))?$value['offset_y']:'';
        $return['blur_radius'] = (is_numeric($value['blur_radius']))?$value['blur_radius']:'';
        $return['color'] = sanitize_hex_color($value['color']);
        return $return;
    }
    
    static function esc($value,$fld){
        $return = [];
        $return['offset_x'] = (is_numeric($value['offset_x']))?self::max_min_100($value['offset_x']):'';
        $return['offset_y'] = (is_numeric($value['offset_y']))?self::max_min_100($value['offset_y']):'';
        $return['blur_radius'] = (is_numeric($value['blur_radius']))?self::max_min_100($value['blur_radius']):'';
        $return['color'] = sanitize_hex_color($value['color']);
        return $return;
    }

    static function max_min_100($value){
        if($value > 100){ $value = 100; }
        if($value < -100){ $value = -100; }
        return $value;
    }

}
