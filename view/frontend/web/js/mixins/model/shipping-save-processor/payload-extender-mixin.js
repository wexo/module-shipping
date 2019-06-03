define([
    'mage/utils/wrapper',
    'Wexo_Shipping/js/model/shipping-data-payload',
], function(wrapper, shippingDataPayload) {
    'use strict';

    return function(payloadExtender) {

        /** Override default place order action and add agreement_ids to request */
        return wrapper.wrap(payloadExtender, function(originalAction, payload) {
            payload = originalAction(payload);

            payload.addressInformation['extension_attributes']['wexo_shipping_data'] = JSON.stringify(
                shippingDataPayload(),
            );

            return payload;
        });
    };
});
