/**
 * Initialization widget for redirect
 *
 * @method click()
 */
define([
    'jquery',
    'mage/cookies'
], function($) {
    "use strict";

    $.widget('mage.awRbsliderSendClickStatistics', {
        options: {
            bannerId: null,
            slideId: null
        },

        /**
         * Initialize widget
         */
        _create: function () {
            this.element.on('click', $.proxy(this.click, this));
            this.element.on('mousedown', $.proxy(this.mouseDown, this));
        },

        /**
         * Mouse down event
         *
         * @param {Object} e
         */
        mouseDown: function (e) {
            if (e.which === 2 || e.which === 3) {
                this.click();
            }
        },

        /**
         * Send statistics after click
         */
        click: function () {
            var bannerId = this.options.bannerId,
                slideId = this.options.slideId,
                data;

            if (bannerId && slideId && navigator.sendBeacon) {
                data = new FormData();
                data.append('slide_id', slideId);
                data.append('banner_id', bannerId);
                data.append('ajax', 'true');
                data.append('form_key', $.mage.cookies.get('form_key'));

                navigator.sendBeacon(this.options.url, data);
            }
        },
    });

    return $.mage.awRbsliderSendClickStatistics;
});
