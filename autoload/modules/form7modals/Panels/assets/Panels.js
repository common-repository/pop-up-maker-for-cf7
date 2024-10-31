$ = jQuery;
$(document).ready(function () {
    // fix changed all inputs
    $(window).off('beforeunload');
    $(window).on('beforeunload', function (event) {
        var changed = false;

        $('#wpcf7-admin-form-element :input[type!="hidden"]').each(function () {
            if($(this).closest('.pop_up_maker_for_cf7_settngs').length > 0){ return; };

            if ($(this).is(':checkbox, :radio')) {
                if (this.defaultChecked != $(this).is(':checked')) {
                    console.log('checkbox', this);
                    changed = true;
                }
            } else if ($(this).is('select')) {
                $(this).find('option').each(function () {
                    console.log('option', this);
                    if (this.defaultSelected != $(this).is(':selected')) {
                        changed = true;
                    }
                });
            } else {
                if (this.defaultValue != $(this).val()) {
                    console.log('all_def', this.defaultValue);
                    console.log('all_val', $(this).val());
                    changed = true;
                }
            }
        });

        if (changed) {
            event.returnValue = wpcf7.saveAlert;
            return wpcf7.saveAlert;
        }
    });

});