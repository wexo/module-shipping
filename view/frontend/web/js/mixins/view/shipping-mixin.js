define([
    'Magento_Checkout/js/model/shipping-service',
    'ko',
    'mage/utils/wrapper'
],function (shippingService, ko, wrapper) {
    'use strict';

    var mixin = {
        defaults: {
            rates: ko.pureComputed(function() {
                var rates = shippingService.getShippingRates()();

                rates.sort(function (a, b) {
                    if(!a.extension_attributes || !a.extension_attributes.wexo_shipping_method_sort_order) {
                        return -1;
                    }
                    
                    if(!b.extension_attributes || !b.extension_attributes.wexo_shipping_method_sort_order) {
                        return 1;
                    }

                    return a.extension_attributes.wexo_shipping_method_sort_order -
                        b.extension_attributes.wexo_shipping_method_sort_order;
                });

                return rates;
            })
        },

        initialize: function () {
            this.validateShippingInformation = wrapper.wrap(
                this.validateShippingInformation,
                function (originalFunction) {
                    const result = originalFunction.apply(this, arguments);

                    if (!result) {
                        return result;
                    }

                    return this.source.trigger('wexoShippingData.data.validate');
                }
            );

            return this._super();
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
