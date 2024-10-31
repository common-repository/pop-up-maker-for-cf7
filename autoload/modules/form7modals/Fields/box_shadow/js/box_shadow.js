jQuery(document).ready(function () {

    jQuery('.box_shadow_wrapper').each(function (index, element) {
        jQuery(element).find('input').on('input', function (e) {
            var inputs = jQuery(e.target).closest('.box_shadow_wrapper').find('input');
            console.log(inputs);
            var $offset_x = inputs[0].value;
            var $offset_y = inputs[1].value;
            var $blur_radius = inputs[2].value;
            var $color = inputs[3].value;


            // console.log();
            // var box_shadow_exemple = jQuery(e.target).closest('.box_shadow_wrapper').next();
            var box_shadow_exemple = jQuery(e.target).closest('.box_shadow_wrapper').next();

            box_shadow_exemple.css('box-shadow', $offset_x + "px " + $offset_y + "px " + $blur_radius + "px " + $color);
        })

    });
    // box-shadow: " . $offset_x . "px " . $offset_y . "px " . $blur_radius . "px " . $color . ";
});