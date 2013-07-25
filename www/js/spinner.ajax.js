(function($) {

    $.nette.ext('spinner',
    {
        init: function()
        {
            this.spinner = $('#ajax-spinner');
        },
        start: function()
        {
            this.spinner.removeClass('hide')
        },
        complete: function()
        {
            this.spinner.addClass('hide');
        }
    });

})(jQuery);
