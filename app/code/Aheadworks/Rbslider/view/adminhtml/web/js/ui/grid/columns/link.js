define([
    'Magento_Ui/js/grid/columns/link'
], function (Link) {
    'use strict';

    return Link.extend({
        defaults: {
            link: '${ $.index }_url'
        }
    });
});
