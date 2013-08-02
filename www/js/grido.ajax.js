/**
 * Nette ajax Grido extension.
 * @author Petr Bugyík
 * @param {jQuery} $
 */
;(function($) {
    "use strict";

    $.nette.ext('grido',
    {
        load: function()
        {
            $('.grido').grido();
        },

        success: function(payload)
        {
            $('.grido').trigger('gridoAjaxSuccess', payload);
            $('html, body').animate({scrollTop: 0}, 400); //scroll up after ajax update
        }
    });

})(jQuery);
