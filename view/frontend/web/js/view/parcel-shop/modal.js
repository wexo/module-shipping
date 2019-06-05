define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Wexo_Shipping/js/model/parcel-shop/popup'
], function($, modal, popup) {

    $.widget('wexoShipping.parcelShopModal', modal, {
        defaults: {
            autoOpen:         true,
            modalClass:       'ws-parcelshop-popup',
            responsive:       true,
            clickableOverlay: true,
            title:            'Pickup Points',
            type:             'popup',
            buttons:          []
        },

        _create: function () {
            this._super();
            popup.setModal(this);
        }
    });

    return $.wexoShipping.parcelShopModal
});