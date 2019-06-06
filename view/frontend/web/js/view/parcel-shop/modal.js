define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Wexo_Shipping/js/model/parcel-shop/popup',
    'text!Wexo_Shipping/template/parcel-shop/modal-popup.html',
    'mage/translate'
], function($, modal, popup, modalTpl) {

    $.widget('wexoShipping.parcelShopModal', modal, {
        options: {
            autoOpen: false,
            modalClass: 'ws-parcelshop-popup',
            responsive: true,
            clickableOverlay: true,
            title: $.mage.__('Find a Service Point'),
            type: 'popup',
            buttons: [],
            popupTpl: modalTpl,
            customTpl: modalTpl
        },

        _create: function() {
            this._super();
            popup.setModal(this);
        }
    });

    return $.wexoShipping.parcelShopModal;
});