<?php

namespace PopUpMakerForCF7\modules\form7modals;

use PopUpMakerForCF7\modules\form7modals\Panels\Panels;
use \PopUpMakerForCF7\basic\plugin;

class form7modals
{
    static function init()
    {
        Panels::init();

        add_action('wp_head', function () {
            echo self::get_sended_style();
        });
        add_action('wp_footer', function () {
            echo self::get_sended_script();
        });

        add_filter('wpcf7_submission_result', [__CLASS__, 'send_data_request'],10,2);
    }

    static function send_data_request($request)
    {
        $request[plugin::$storage->get('PLUGIN_NAME') . 'settings'] = unserialize(plugin::$storage->get(plugin::$storage->get('PLUGIN_NAME') . $_POST['_wpcf7'], 'DB'));
        return $request;
    }

    static function get_sended_style()
    {
        return '<style>.sending_modal-active .wpcf7-response-output{ display: none; } .sending_modal.show{ opacity:1 !important;visibility: visible !important; position: fixed; width:100%; height: 100%; top:0; left:0;background-color: rgba(0,0,0,.5); } .sending_modal.show .sending_modal-box{ width:50%;background-color: #fff;position: absolute;top: 50%;transform: translate(-50%, -50%);left: 50%;padding: 2em;}.sending_modal-close{height: 20px;width: 20px;display: block;position: absolute;top: 10px;right: 10px;cursor: pointer;}.sending_modal-close:before,.sending_modal-close:after{content: "";background-color: #000;width: 100%;height: 2px;display: block;position: absolute;top: calc(50% - 1px);transform-origin: 50%;}.sending_modal-close:before{transform: rotate(-45deg);}.sending_modal-close:after{transform: rotate(45deg);}</style>';
    }

    static function get_sended_script()
    {

        return '
            <div class="sending_modal" style="z-index:99999999999; opacity: 0;visibility: hidden;transition: all .5s ease;" ><div class="sending_modal-box"><div class="sending_modal-content"></div><div class="sending_modal-close"></div></div></div>

            <script>
                var events = [
                    "wpcf7invalid", //Fires when an Ajax form submission has completed successfully, but mail hasn’t been sent because there are fields with invalid input.
                    "wpcf7spam", //Fires when an Ajax form submission has completed successfully, but mail hasn’t been sent because a possible spam activity has been detected.
                    "wpcf7mailsent", //Fires when an Ajax form submission has completed successfully, and mail has been sent.
                    "wpcf7mailfailed", //Fires when an Ajax form submission has completed successfully, but it has failed in sending mail.
                    "wpcf7submit" //Fires when an Ajax form submission has completed successfully, regardless of other incidents.
                ];

                function sending_modal_show(settings){
                    var container = document.querySelector(".sending_modal");
                    var content = container.querySelector(".sending_modal-box");
                    container.querySelector(".sending_modal-content").innerHTML = settings.message_confirm;
                    
                    content.style="";
                    if(settings.hasOwnProperty("max_width")){ content.style.maxWidth = settings.max_width+"px"; }
                    if(settings.hasOwnProperty("min_height")){ content.style.minHeight = settings.min_height+"px"; }
                    
                    if(settings.hasOwnProperty("border_width")){ 
                        var bdcolor = (settings.hasOwnProperty("border_color"))?settings.border_color:"";
                        content.style.border = "solid "+settings.border_width+"px "+bdcolor; 
                    }

                    if(settings.hasOwnProperty("background_color")){  content.style.backgroundColor = settings.background_color; }
                    if(settings.hasOwnProperty("background_overlay")){  container.style.backgroundColor = settings.background_overlay; }
                    if(settings.hasOwnProperty("color_message")){  content.style.color = settings.color_message; }
                    if(settings.hasOwnProperty("shadow_box")){  
                        var offset_x = (settings.shadow_box.hasOwnProperty("offset_x"))?settings.shadow_box.offset_x:"0";
                        var offset_y = (settings.shadow_box.hasOwnProperty("offset_y"))?settings.shadow_box.offset_y:"0";
                        var blur_radius = (settings.shadow_box.hasOwnProperty("blur_radius"))?settings.shadow_box.blur_radius:"0";
                        var color = (settings.shadow_box.hasOwnProperty("color"))?settings.shadow_box.color:"#000";
                        content.style.boxShadow =  offset_x+"px "+offset_y+"px "+blur_radius+"px "+color; 
                    }

                    container.classList.add("show");
                }

                function sending_modal_close(triger){
                    var container = document.querySelector(".sending_modal");
                    container.classList.remove("show");
                }

                document.addEventListener( \'wpcf7invalid\', function( event ) {
                    if(
                        event.detail.apiResponse.hasOwnProperty("pop_up_maker_for_cf7settings") &&
                        event.detail.apiResponse.pop_up_maker_for_cf7settings.hasOwnProperty("activate") &&
                        event.detail.apiResponse.pop_up_maker_for_cf7settings.activate.hasOwnProperty("active") &&
                        event.detail.apiResponse.pop_up_maker_for_cf7settings.activate.active == "on"
                        ){
                            event.target.classList.add("sending_modal-active");
                        }
                }, false );

                document.addEventListener("wpcf7mailsent", function( event ) {
                    if(
                        event.detail.apiResponse.hasOwnProperty("pop_up_maker_for_cf7settings") &&
                        event.detail.apiResponse.pop_up_maker_for_cf7settings.hasOwnProperty("activate") &&
                        event.detail.apiResponse.pop_up_maker_for_cf7settings.activate.hasOwnProperty("active") &&
                        event.detail.apiResponse.pop_up_maker_for_cf7settings.activate.active == "on"
                        ){
                            event.target.classList.add("sending_modal-active");
                            sending_modal_show(event.detail.apiResponse.pop_up_maker_for_cf7settings);
                        }
                }, false );


                document.addEventListener("click", function( event ){
                    var sending_modal_closed = false;

                    if(event.target.matches(".sending_modal-close")){ sending_modal_closed = true; var sending_modal_trigger = "Btn"; }
                    else if(event.target.matches(".sending_modal.show")){ sending_modal_closed = true; var sending_modal_trigger = "Overlay"; }

                    if(sending_modal_closed){ sending_modal_close(sending_modal_trigger); }
                });

                document.addEventListener("keydown", function(event){
                    if(event.key === "Escape"){
                        sending_modal_close("Escape");
                    }
                });

            </script>
        ';
    }
}
