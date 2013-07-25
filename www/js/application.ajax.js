$(function(){

    //register Grido to nette extensions
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

    //uncomment below if you want to change the datepicker...
    //$.fn.grido.Grid.prototype.initDatepicker = function() {
    //    this.$element.on('focus', 'input.date').mySuperTruperDatePicker();
    //};

    //init nette ajax
    $.nette.init();
});
