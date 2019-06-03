define([
    'uiComponent',
    'ko',
    'underscore',
    'Magento_Checkout/js/model/quote',
], function(Component, ko, _, quote) {

    return Component.extend({
        defaults: {
            template: 'Wexo_Shipping/checkout/shipping/additional-data.html',
            shippingMethod: quote.shippingMethod
        },
        currentAdditionalData: null,

        initialize: function() {
            this._super();

            this.currentAdditionalData = ko.pureComputed(function() {
                if (!quote.shippingMethod()) {
                    return null;
                }
                if (!quote.shippingMethod().extension_attributes) {
                    return null;
                }
                return _.findWhere(this.elems(), {
                    index: quote.shippingMethod().carrier_code + '-' +
                        quote.shippingMethod().extension_attributes.wexo_shipping_method_type_handler
                });
            }, this);

            return this;
        }
    });
});
