<?php

namespace PopUpMakerForCF7\modules\depsChecker;

class depsChecker
{
    static function init()
    {
        require_once 'TGM-Plugin-Activation-2.6.1/class-tgm-plugin-activation.php';
        add_action('tgmpa_register', [__CLASS__,'register_deps']);
    }

    static function register_deps()
    {
        $plugins = [
            array(
                'name'      => 'Contact Form 7',
                'slug'      => 'contact-form-7',
                'required'  => true
            ),
        ];

        $config = array(
            'id'           => 'pop_up_maker_for_cf7',
            'menu'         => 'pop_up_maker_for_cf7_install_plugins',
            'parent_slug'  => 'plugins.php',
            'capability'   => 'manage_options',
            'has_notices'  => true,
            'dismissable'  => false,
            'dismiss_msg'  => '',
            'is_automatic' => false,
            'message'      => '',
        );

        tgmpa_noconflict($plugins, $config);
    }

}
