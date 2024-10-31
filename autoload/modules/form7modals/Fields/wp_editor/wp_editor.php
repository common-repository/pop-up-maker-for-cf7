<?php

namespace PopUpMakerForCF7\modules\form7modals\Fields\wp_editor;

use PopUpMakerForCF7\modules\form7modals\Panels\PanelsSending\PanelsSending;
use \PopUpMakerForCF7\basic\plugin;
use \PopUpMakerForCF7\modules\form7modals\Fields\Fields;
use \PopUpMakerForCF7\modules\form7modals\Fields\Field;

class wp_editor extends Field
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
        $array_name = $prefix . '_wp_editor__' . $options['name'] . '__';
        $value = PanelsSending::get_option(Fields::$form_id,$options['name']);
        if(empty($value) && isset($options['default'])){
            $value = $options['default'];
        }

        ob_start();
        wp_editor($value, $array_name,[
            'default_editor' => 'TinyMCE',
            'textarea_rows' => '10',
        ]);
        $editor = ob_get_clean();
        
        $HTML = "
        <tr>
            <th scope='row'>
                <label>" . $options['label'] . "</label>
            </th>
            <td>
                ". $editor ."
            </td>
        </tr>
        ";

        return $HTML;
    }
}
