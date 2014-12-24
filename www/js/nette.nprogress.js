/**
 * NProgress extension for nette.ajax.js
 * @param {jQuery} $
 * @param {Window} window
 * @link https://github.com/rstacruz/nprogress
 */
(function($, window) {
    "use strict";

    $.nette.ext('nprogress',
    {
        start: function()
        {
            window.NProgress.start();
        },

        complete: function()
        {
            window.NProgress.done();
        }
    });

})(jQuery, window);
