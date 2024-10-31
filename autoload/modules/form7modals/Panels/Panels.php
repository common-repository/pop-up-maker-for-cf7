<?php

namespace PopUpMakerForCF7\modules\form7modals\Panels;

use PopUpMakerForCF7\modules\form7modals\Fields\Fields;

class Panels
{
    static function init()
    {
        Fields::init();
        PanelsSending\PanelsSending::init();

        add_action(\PopUpMakerForCF7\basic\plugin::$storage->get('PLUGIN_NAME') . '_admin_assets', [__CLASS__, 'deps']);
    }

    static function deps($storage)
    {
        if(wp_script_is('wpcf7-admin')){
            wp_enqueue_script('panels_script', $storage->get('PLUGIN_URL') . '/autoload/modules/form7modals/Panels/assets/Panels.js', ['jquery', 'wpcf7-admin']);
        }
    }

}
