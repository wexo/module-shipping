var config = {
    shim: {
        mapbox: {
            exports: 'mapboxgl',
        },
    },
    map: {
        '*': {
            mapbox: 'Wexo_Shipping/js/mapbox',
        },
    },
    paths: {
        mapboxgl: 'Wexo_Shipping/lib/mapbox',
        Fuse: 'Wexo_Shipping/lib/fuse.min',
    },
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Wexo_Shipping/js/mixins/model/shipping-save-processor/payload-extender-mixin': true,
            },
        },
    },
};
