/**
 * Grido extensions.
 *
 * @author Petr Bugyík
 * @param {jQuery} $
 * @param {Document} document
 * @param {Window} window
 * @param {Grido} Grido
 * @param {undefined} undefined
 */
;
(function($, window, document, Grido, undefined) {
    /*jshint laxbreak: true, expr: true */
    "use strict";

    Grido.Grid.prototype.onInit = function()
    {
        this.initDatepicker();
        this.initSuggest();
    };

    Grido.Grid.prototype.initDatepicker = function()
    {
        var _this = this;
        var format = _this.options.datepicker.format.toUpperCase();
        this.$element.on('focus', 'input.date', function() {
            $(this).daterangepicker(
            {
                singleDatePicker: true,
                showDropdowns: true,
                format: format,
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            });
        });

        this.$element.on('focus', 'input.daterange', function() {
            $(this).daterangepicker(
            {
                format: format,
                showDropdowns: true,
                ranges: {
                 'Today': [moment(), moment()],
                 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                 'This Month': [moment().startOf('month'), moment().endOf('month')],
                 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate: moment()
            });
        });
    };

    Grido.Grid.prototype.initSuggest = function()
    {
        if ($.fn.typeahead === undefined) {
            console.error('Plugin "typeahead.js" is missing!');
            return;
        }

        var _this = this;
        this.$element.find('input.suggest').each(function()
        {
            var url = $(this).data('grido-suggest-handler'),
                wildcard = $(this).data('grido-suggest-replacement');

            var options = {
                limit: $(this).data('grido-suggest-limit'),
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: url.replace(wildcard, '%QUERY')
                }
            };

            if (window.NProgress !== undefined) {
                options.remote.ajax = {
                    beforeSend: $.proxy(window.NProgress.start),
                    complete: $.proxy(window.NProgress.done)
                };
            }

            var source = new Bloodhound(options);
            source.initialize();

            $(this).typeahead(null, {
                displayKey: function(item) {
                    return item;
                },
                source: source.ttAdapter()
            });

            $(this).on('typeahead:selected', function() {
                _this.sendFilterForm();
            });
        });

        this.$element.on('keyup', 'input.suggest', function(event) {
            var key = event.keyCode || event.which;
            if (key === 13) { //enter
                event.stopPropagation();
                event.preventDefault();

                _this.sendFilterForm();
            }
        });
    };

    Grido.Ajax.prototype.onSuccessEvent = function(params, uri)
    {
        History.pushState(params, document.title, '?' + uri);
    };

    /**
     * @param {string} url
     * @param {Element|null} ussually Anchor or Form
     * @param {event|null} event causing the request
     */
    Grido.Ajax.prototype.doRequest = function(url, ui, e)
    {
        if ($.fn.netteAjax === undefined) {
            console.error('Plugin "nette.ajax.js" is missing!');
            $.get(url);
        } else {
            $.nette.ajax({url: url}, ui, e);
        }
    };

})(jQuery, window, document, window.Grido);
