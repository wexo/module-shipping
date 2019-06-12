define([
    'uiClass'
], function(Class) {

    return Class.extend({
        defaults: {
            element: null,
            key: null,
            markers: []
        },

        triggerResize: function() {
            // Not Implemented
        },

        cleanMarkers: function() {
            // Not Implemented
        },

        addMarker: function(lng, lat) {
            // Not Implemented
        },

        moveTo: function(lng, lat) {
            // Not Implemented
        },

        create: function() {
            // Not Implemented
        },

        /**
         * Is allowed to view map
         */
        isAllowed: function() {

        }
    });
});