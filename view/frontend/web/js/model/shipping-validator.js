define([
    'Magento_Checkout/js/model/quote',
    'uiRegistry'
], function(quote, uiRegistry) {
    'use strict';

    return {

        /**
         * @returns {boolean}
         */
        validate: function() {
            var checkoutProvider = uiRegistry.get('checkoutProvider');
            checkoutProvider.set('params.invalid', false);
            checkoutProvider.trigger('wexoShippingData.data.validate');
            return !checkoutProvider.get('params.invalid');
        }
    };
});
