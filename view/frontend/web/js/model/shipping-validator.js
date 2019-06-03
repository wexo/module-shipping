define([
    'Magento_Checkout/js/model/quote'
], function(quote) {
    'use strict';

    return {
        validate: function() {
            console.log(quote);
            return true;
        }
    }
});
