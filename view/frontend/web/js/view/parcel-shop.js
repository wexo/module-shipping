define([
    'uiComponent',
    'ko',
    'jquery',
    'underscore',
    'Wexo_Shipping/js/model/parcel-shop/popup',
    'Wexo_Shipping/js/model/map',
    'Magento_Checkout/js/model/quote',
    'mage/translate',
    'Magento_Checkout/js/action/set-shipping-information',
    'matchMedia'
], function(Component, ko, $, _, parcelShopPopup, map, quote, $t, setShippingInformation) {

    return Component.extend({
        defaults: {
            mobileWidth: '648px',
            template: 'Wexo_Shipping/parcel-shop',
            modalTemplate: 'Wexo_Shipping/parcel-shop/popup',
            modalItemTemplate: 'Wexo_Shipping/parcel-shop/parcel-shop-entry',
            chosenItemTemplate: 'Wexo_Shipping/parcel-shop/chosen-item',

            provider: 'checkoutProvider',
            deps: 'checkoutProvider',
            label: $t('Find a Service Point'),

            links: {
                chosenParcelShop: '${ $.provider }:wexoShippingData.parcelShop',
                wexoShippingData: '${ $.provider }:wexoShippingData',

                shippingCountryId: '${ $.provider }:shippingAddress.country_id',
                shippingPostcode: '${ $.provider }:shippingAddress.postcode'
            },

            listen: {
                '${ $.provider }:shippingAddress.data.validate': 'validateAddress',
                '${ $.provider }:wexoShippingData.data.validate': 'validate'
            },

            shippingMethod: quote.shippingMethod,
            parcelShops: [],
            parcelShopSearcher: null,

            activeParcelShop: null
        },

        initialize: function() {
            this._super();

            this.chosenParcelShop.subscribe(function(value, oldValue) {
                oldValue && this._saveParcelShop();
                this.activeParcelShop(value);
            }, this);

            this.shippingCountryId.subscribe(this.chosenParcelShop.bind(this, null));
            this.activeParcelShop.subscribe(this._onActiveParcelShop.bind(this));

            this.shippingMethod.subscribe(function(newVal, oldVal) {
                oldVal && this.chosenParcelShop(null);
            }, this);

            return this;
        },

        initObservable: function() {
            return this._super()
                .observe('parcelShops label chosenParcelShop postcode shippingPostcode shippingCountryId')
                .observe('wexoShippingData activeParcelShop');
        },

        /**
         * @returns {{isValid: boolean, target: exports}}
         */
        validateAddress: function() {
            this.source.trigger('wexoShippingData.data.validate');
        },

        /**
         * @returns {{isValid: boolean, target: exports}}
         */
        validate: function() {
            var isValid = !!this.chosenParcelShop();

            if (!isValid) {
                this.source.set('params.invalid', true);
            }

            return {
                isValid: isValid,
                target: this
            };
        },

        /**
         * @returns {*}
         */
        getFields: function() {
            return this.getRegion('fields');
        },

        /**
         * @private
         */
        _saveParcelShop: function() {
            if (this.shippingCountryId()) {
                setShippingInformation();
            }
        },

        search: function() {
            if (!this.parcelShopSearcher) {
                throw 'parcelShopSearcher is null in Wexo_Shipping/js/view/parcel-shop';
            }

            this.parcelShopSearcher(this.source.get('wexoShippingData'), this.shippingCountryId(), this)
                .done(function(result) {
                    this.parcelShops(result);
                    parcelShopPopup.open(this, this._onPopupShow.bind(this));

                    if (result && result.length) {
                        if (this.chosenParcelShop()) {
                            var foundParcelShop = _.findWhere(result, {
                                number: this.chosenParcelShop().number
                            });
                            if (foundParcelShop) {
                                this.activeParcelShop(foundParcelShop);
                                return;
                            }
                        }

                        this.activeParcelShop(result[0]);
                    }
                }.bind(this));
        },

        _onPopupShow: function() {

        },

        getModalTemplate: function() {
            return this.modalTemplate;
        },

        getModalItemTemplate: function() {
            return this.modalItemTemplate;
        },

        getChosenItemTemplate: function() {
            return this.chosenItemTemplate;
        },

        setMapElement: function(element) {
            map.changeElement(element);
            this.activeParcelShop.valueHasMutated();
        },

        /**
         * @returns {*}
         */
        getPopupText: function() {
            return ko.pureComputed(function() {
                return $t('%1 service points').replace('%1', this.parcelShops().length);
            }, this);
        },

        /**
         * @private
         */
        _onActiveParcelShop: function(parcelShop) {
            if (parcelShopPopup.modal().options.isOpen && parcelShopPopup.component() === this) {

                map.clearMarkers();
                map.addMarker(
                    parcelShop.longitude,
                    parcelShop.latitude
                );
                setTimeout(function() {
                    map.moveTo(
                        parcelShop.longitude,
                        parcelShop.latitude
                    );
                });

                if(window.matchMedia('(max-width: ' + this.mobileWidth + ')').matches) {
                    var $modalInnerWrapper = $('.ws-parcelshop-popup.modal-popup.modal-slide .modal-inner-wrap');
                    $modalInnerWrapper.animate({
                        scrollTop: $modalInnerWrapper.height()
                    }, 250);
                }
            }
        },

        /**
         * @returns {string}
         */
        formatOpeningHours: function() {
            return '';
        },

        setChoosenParcelShop: function(parcelShop) {
            this.chosenParcelShop(parcelShop);
            this._saveParcelShop();
            parcelShopPopup.close();
        },

        onModalClose: function(modal) {

        },

        showMap: function(parcelShop, element) {
            map.changeElement(element);
            map.clearMarkers();
            map.addMarker(
                parcelShop.longitude,
                parcelShop.latitude
            );

            setTimeout(function() {
                map.moveTo(
                    parcelShop.longitude,
                    parcelShop.latitude
                );
            })
        }
    });
});