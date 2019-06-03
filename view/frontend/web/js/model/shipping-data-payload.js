define([
    'uiRegistry',
], function(uiRegistry) {

    return function() {
        return uiRegistry.get('checkoutProvider').get('wexoShippingData');
    };
});
