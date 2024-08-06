define([
    'Magento_Ui/js/form/components/fieldset'
], function (fieldset) {
    'use strict';

    return fieldset.extend({

        /**
         * Show element.
         */
        show: function () {
            this.visible(true);

            return this;
        },

        /**
         * Hide element.
         */
        hide: function () {
            this.visible(false);

            return this;
        }
    });
});