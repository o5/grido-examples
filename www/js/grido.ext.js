/**
 * Grido extensions.
 *
 * @author Petr Bugyík
 * @param {jQuery} $
 * @param {Window} window
 * @param {Grido} Grido
 * @param {undefined} undefined
 */
;(function($, window, Grido, undefined) {
    /*jshint laxbreak: true, expr: true */
    "use strict";

    Grido.Grid.prototype.onInit = function()
    {
        this.initDatepicker();
        this.initSuggest();
    };

    /**
    * Init datepicker.
    * @link https://rawgithub.com/digitalBush/jquery.maskedinput/master/dist/jquery.maskedinput.js
    * @link https://rawgithub.com/Aymkdn/Datepicker-for-Bootstrap/master/bootstrap-datepicker.js
    */
    Grido.Grid.prototype.initDatepicker = function()
    {
        var _this = this;
        this.$element.on('focus', 'input.date', function() {
            $.fn.mask === undefined
                ? console.error('Plugin "jquery.maskedinput.js" is missing!')
                : $(this).mask(_this.options.datepicker.mask);

            $.fn.datepicker === undefined
                ? console.error('Plugin "bootstrap-datepicker.js" is missing!')
                : $(this).datepicker({format: _this.options.datepicker.format});
        });
    };

    /**
     * Init suggestion.
     * @link https://rawgithub.com/o5/bootstrap/master/js/bootstrap-typeahead.js
     */
    Grido.Grid.prototype.initSuggest = function()
    {
        if ($.fn.typeahead === undefined) {
            console.error('Plugin "bootstrap-typeahead.js" is missing!');
            return;
        }

        var _this = this;
        this.$element
            .on('keyup', 'input.suggest', function(event) {
                var key = event.keyCode || event.which;
                if (key === 13) { //enter
                    event.stopPropagation();
                    event.preventDefault();

                    _this.sendFilterForm();
                }
            })
            .on('focus', 'input.suggest', function() {
                $(this).typeahead({
                    source: function (query, process) {
                        if (!/\S/.test(query)) {
                            return false;
                        }

                        var link = this.$element.data('grido-suggest-handler'),
                            replacement = this.$element.data('grido-suggest-replacement');

                        return $.get(link.replace(replacement, query), function (items) {
                            //TODO local cache??
                            process(items);
                        }, "json");
                    },

                    updater: function (item) {
                        this.$element.val(item);
                        _this.sendFilterForm();
                    },

                    autoSelect: false //improvement of original bootstrap-typeahead.js
                });
        });
    };

    Grido.Ajax.prototype.registerHashChangeEvent = function()
    {
        $.fn.hashchange === undefined
            ? console.error('Plugin "jquery.hashchange.js" is missing!')
            : $(window).hashchange($.proxy(this.handleHashChangeEvent, this));

        this.handleHashChangeEvent();
    };

    /**
     * @param {string} url
     * @link https://rawgithub.com/vojtech-dobes/nette.ajax.js/master/nette.ajax.js
     */
    Grido.Ajax.prototype.doRequest = function(url)
    {
        $.fn.netteAjax === undefined
            ? $.get(url)
            : $.nette.ajax({url: url});
    };

})(jQuery, window, window.Grido);
