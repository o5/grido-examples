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
            this.selector = $('.grido');
            this.selector.grido();
        },

        success: function(payload)
        {
            if (payload.grido) {
                this.selector.trigger('success.ajax.grido', payload);

                //scroll up after ajax update
                $('html, body').animate({scrollTop: 0}, 400);
            }
        }
    });

})(jQuery);
