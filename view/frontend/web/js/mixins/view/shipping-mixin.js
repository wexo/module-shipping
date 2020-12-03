define([
    'Magento_Checkout/js/model/shipping-service',
    'ko'
],function (shippingService, ko) {
    'use strict';

    var mixin = {
        defaults: {
            rates: ko.pureComputed(function() {
                var rates = shippingService.getShippingRates()();

                console.log(rates);
                rates.sort(function (a, b) {
                    if(!a.extension_attributes.wexo_shipping_method_sort_order) {
                        return -1;
                    }
                    if(!b.extension_attributes.wexo_shipping_method_sort_order) {
                        return 1;
                    }

                    return a.extension_attributes.wexo_shipping_method_sort_order -
                        b.extension_attributes.wexo_shipping_method_sort_order;
                });

                return rates;
            })
        },
    };

    return function (target) {
        return target.extend(mixin);
    };
});
