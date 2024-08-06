/**
 * Initialization widget to upload html content by Ajax
 *
 * @method ajax(placeholders)
 * @method replacePlaceholder(placeholder, html)
 */
define([
    'jquery'
], function($) {
    "use strict";

    $.widget('mage.awRbsliderAjax', {
        options: {
            url: '/',
            dataPattern: 'aw-rbslider-banner-id'
        },

        /**
         * Initialize widget
         */
        _create: function () {
            var placeholders = $('[data-' + this.options.dataPattern + ']');

            if (placeholders && placeholders.length) {
                this.ajax(placeholders);
            }
        },

        /**
         * Send AJAX request
         * @param {Object} placeholders
         */
        ajax: function (placeholders) {
            var self = this,
                data = {
                    bannerIds: []
                };

            placeholders.each(function() {
                data.bannerIds.push($(this).data(self.options.dataPattern));
            });
            data.bannerIds = JSON.stringify(data.bannerIds.sort());
            $.ajax({
                url: this.options.url,
                data: data,
                type: 'GET',
                cache: false,
                dataType: 'json',
                context: this
            });
        }
    });

    return $.mage.awRbsliderAjax;
});
