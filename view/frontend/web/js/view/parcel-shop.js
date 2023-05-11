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
                postcode: '${ $.provider }:wexoShippingData.postcode',

                shippingCountryId: '${ $.provider }:shippingAddress.country_id',
                shippingPostcode: '${ $.provider }:shippingAddress.postcode'
            },

            shippingMethod: quote.shippingMethod,
            parcelShops: [],
            parcelShopSearcher: null,
            activeParcelShop: null,
            errorMessage: '',

            oldShippingMethodType: null
        },

        initialize: function() {
            this._super();
            this.source.on('shippingAddress.data.validate', this.validateAddress.bind(this));
            this.source.on('wexoShippingData.data.validate', this.validate.bind(this));

            this.chosenParcelShop.subscribe(function(value, oldValue) {
                oldValue && this._saveParcelShop();
                this.activeParcelShop(value);
                this.errorMessage('');
            }, this);

            this.shippingCountryId.subscribe(this.chosenParcelShop.bind(this, null));
            this.activeParcelShop.subscribe(this._onActiveParcelShop.bind(this));
            this.shippingPostcode.subscribe(this.updatePostcode.bind(this));

            if(this.shippingPostcode()){
                this.updatePostcode(this.shippingPostcode());
            }

            quote.shippingMethod.subscribe(function(newVal) {
                if (!newVal || !newVal.carrier_code || !newVal.method_code) {
                    this.chosenParcelShop(null);
                    return;
                }

                const key = (newVal.carrier_code + newVal.method_code).toLowerCase();
                if (key !== this.oldShippingMethodType) {
                    this.chosenParcelShop(null);
                }
                this.oldShippingMethodType = key;
            }, this);

            this.isChosenShippingMethod = ko.pureComputed(function() {
                if (!this.shippingMethod() || !this.shippingMethod().extension_attributes) {
                    return false;
                }
                return (this.shippingMethod().carrier_code + '-' +
                    this.shippingMethod().extension_attributes.wexo_shipping_method_type_handler) === this.index;
            }, this);

            return this;
        },

        initObservable: function() {
            return this._super()
                .observe('parcelShops disableFields label chosenParcelShop postcode shippingPostcode shippingCountryId')
                .observe('wexoShippingData activeParcelShop errorMessage');
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
            var isValid = !this.isChosenShippingMethod() || !!this.chosenParcelShop();

            if (!isValid) {
                this.source.set('params.invalid', true);
                this.errorMessage($t('You must choose a Service Point!'));
            }

            if(!isValid) {
                if($('.wexo-shipping-additional .field-error').length) {
                    $('body, html').animate({
                        scrollTop: jQuery('.wexo-shipping-additional').offset().top - (window.screen.height / 4)
                    }, 1000);
                }
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

        hasInvalidFields: function() {
          const validationResults = this.getFields()()[0].elems().map((field) => field.validate().valid);
          return validationResults.some((isValid) => isValid === false);
        },

        search: function() {

            if (this.hasInvalidFields()) {
              return;
            }

            this.errorMessage('');
            if (!this.parcelShopSearcher) {
                throw 'parcelShopSearcher is null in Wexo_Shipping/js/view/parcel-shop';
            }

            this.parcelShopSearcher(this.source.get('wexoShippingData'), this.shippingCountryId(), this)
                .done(function(result) {
                    this.parcelShops(result);

                    if (result && result.length) {
                        parcelShopPopup.open(this, this._onPopupShow.bind(this));

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
                        this.errorMessage('');
                    }
                    else {
                        this.errorMessage($t('Sorry, we could not find any service points in your area'));
                    }
                }.bind(this));
        },

        _onPopupShow: function() {

        },

        updatePostcode(postcode) {
            if(!this.chosenParcelShop()){
                this.postcode(postcode);
            }
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
            setTimeout(function() {
                map.changeElement(element);
            })
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
                if(parcelShop.longitude && parcelShop.latitude) {
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
                }

                if (window.matchMedia('(max-width: ' + this.mobileWidth + ')').matches) {
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
            setTimeout(function() {
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
            });
        }
    });
});
