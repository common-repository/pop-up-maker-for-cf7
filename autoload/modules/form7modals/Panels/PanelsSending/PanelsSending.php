<?php

	namespace PopUpMakerForCF7\modules\form7modals\Panels\PanelsSending;

	use PopUpMakerForCF7\modules\form7modals\Fields\Fields;
	use \PopUpMakerForCF7\basic\plugin;

	class PanelsSending {
		/**
		 * @var array
		 */
		static $data = array();

		static function init() {
			$default_text_send = '';

			if ( function_exists( 'wpcf7_messages' ) ) {
				$default_messages_f7 = wpcf7_messages();

				if ( isset( $default_messages_f7['mail_sent_ok'] ) ) {
					$default_text_send = $default_messages_f7['mail_sent_ok']['default'];
				}

			}

			self::$data = array(
				'id' => 'sending_modal_settings',
				'title' => __( 'Sending modal', 'pop-up-maker-for-cf7' ),
				'options' => array(),
				'fields' => array(
					'activate' => array(
						'type' => 'checkbox',
						'label' => '',
						'colspan' => '2',
						'options' => array(
							'active' => 'Activate modal messages',
						),
					),
					'max_width' => array(
						'type' => 'number',
						'label' => __( 'Max. width px', 'pop-up-maker-for-cf7' ),
					),
					'min_height' => array(
						'type' => 'number',
						'label' => __( 'Min. height px', 'pop-up-maker-for-cf7' ),
					),
					'border_width' => array(
						'type' => 'number',
						'label' => __( 'Border width px', 'pop-up-maker-for-cf7' ),
					),
					'border_color' => array(
						'type' => 'colorpicker',
						'label' => __( 'Border color', 'pop-up-maker-for-cf7' ),
						'default' => '#000',
					),
					'background_color' => array(
						'type' => 'colorpicker',
						'label' => __( 'Background color', 'pop-up-maker-for-cf7' ),
						'default' => '#fff',
					),
					'background_overlay' => array(
						'type' => 'colorpicker',
						'label' => __( 'Background overlay color', 'pop-up-maker-for-cf7' ),
						'default' => '#00000051',
					),
					'color_message' => array(
						'type' => 'colorpicker',
						'label' => __( 'Color message', 'pop-up-maker-for-cf7' ),
						'default' => '#000',
					),
					'shadow_box' => array(
						'type' => 'box_shadow',
						'label' => __( 'Shadow box', 'pop-up-maker-for-cf7' ),
					),
					'message_confirm' => array(
						'type' => 'wp_editor',
						'label' => __( 'Messages of Successfulness', 'pop-up-maker-for-cf7' ),
						'default' => $default_text_send,
					),
				),
			);

			add_filter( 'wpcf7_editor_panels', array(__CLASS__, 'register') );
			add_action( 'wpcf7_save_contact_form', array(__CLASS__, 'save'), 10, 3 );

			add_action( plugin::$storage->get( 'PLUGIN_NAME' ) . '_admin_assets', array(__CLASS__, 'deps') );

		}

		/**
		 * @param $storage
		 */
		static function deps( $storage ) {
			wp_enqueue_style( 'panel_sending_style', $storage->get( 'PLUGIN_URL' ) . '/autoload/modules/form7modals/Panels/assets/PanelsSending.css' );
		}

		/**
		 * @param $panels
		 * @return mixed
		 */
		static function register( $panels ) {
			$panels['modal_show_form'] = array(
				'title' => self::$data['title'],
				'callback' => array(__CLASS__, 'render'),
			);

			return $panels;
		}

		/**
		 * @param array $all_form_data
		 * @return mixed
		 */
		static function get_values_sumple_name( $all_form_data = array() ) {
			$data = array();

			foreach ( $all_form_data as $key => $value ) {
				$re = '/wp_editor__(.*)__/';
				preg_match( $re, $key, $matches );

				if ( isset( $matches[1] ) ) {$data[$matches[1]] = $value;}

			}

			return $data;
		}

		/**
		 * @param $contact_form
		 * @param $args
		 * @param $context
		 */
		static function save( $contact_form, $args, $context ) {
			$data = array();
			$key = plugin::$storage->get( 'PLUGIN_NAME' ) . $contact_form->id();

			$fields_name_simple = self::get_values_sumple_name( $args );
			$data = array_merge( $data, $fields_name_simple );

			if ( isset( $args[$key] ) && ! empty( $args[$key] ) ) {
				$data = array_merge( $data, $args[$key] );

				foreach ( $data as $name_field => $value_fld ) {
					$fld = self::$data['fields'][$name_field];
					$class_name = '\PopUpMakerForCF7\modules\form7modals\Fields\\' . $fld['type'] . '\\' . $fld['type'];

					if ( method_exists( $class_name, 'sanitize' ) ) {
						$data[$name_field] = $class_name::sanitize( $value_fld, $fld );
					}

				}

				plugin::$storage->set( $key, maybe_serialize( $data ), 'DB' );
			}

		}

		/**
		 * @param $form_id
		 * @param $name
		 * @return mixed
		 */
		static function get_option( $form_id, $name ) {
			$key = plugin::$storage->get( 'PLUGIN_NAME' ) . $form_id;
			$defaults = array(
				'activate' => array('active' => 0),
				'max_width' => '900',
				'min_height' => '0',
			);
			$values = plugin::$storage->get( $key, 'DB' );

			if ( is_string( $values ) ) {
				$values = unserialize( $values );
			}

			$args = wp_parse_args( $values, $defaults );

			if ( isset( $args[$name] ) && isset( $args[$name] ) ) {
				$return_value = $args[$name];

				$fld = self::$data['fields'][$name];
				$class_name = '\PopUpMakerForCF7\modules\form7modals\Fields\\' . $fld['type'] . '\\' . $fld['type'];

				if ( method_exists( $class_name, 'esc' ) ) {
					$return_value = $class_name::esc( $return_value, $fld );
				}

				self::$data['options'][$name] = $return_value;
				return $return_value;
			}

			return false;
		}

		/**
		 * @param $contact_form
		 */
	static function render( $contact_form ) {?>

        <div class="<?php echo plugin::$storage->get( 'PLUGIN_NAME' ) ?>_settngs">
            <?php $id = self::$data['id'];
            		do_action( 'show_panel_' . $id, $id );?>
            <table>
                <tbody>

                    <?php
                    	Fields::$form_id = $contact_form->id();

                    			foreach ( self::$data['fields'] as $field_name => $field ) {
                    				Fields::make( $field['type'], array_merge( $field, array('name' => $field_name) ) );
                    			}
                    		?>
                </tbody>
            </table>

        </div>

<?php }

	}
