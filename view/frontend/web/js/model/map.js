define([
    'ko',
    './map-providers/map-providers',
    'underscore'
], function(ko, mapProviders, _) {

    var mapElement = document.createElement('div');
    var currentMapProvider = ko.observable(null);
    var mapProviders = _.mapObject(mapProviders, function(Provider, key) {
        return new Provider({
            element: mapElement,
            key: key
        });
    });

    /**
     * @returns {*}
     * @private
     */
    function getMapProvider() {
        return mapProviders[currentMapProvider()];
    }

    return {
        currentMapProvider: currentMapProvider,
        mapProviders: mapProviders,

        changeElement: function(element) {
            if (!currentMapProvider()) {
                var mapProvider = _.find(mapProviders, function(mapProvider) {
                    return mapProvider.isAllowed();
                });
                currentMapProvider(
                    mapProvider ? mapProvider.key : null
                );
                getMapProvider().create();
            }

            element.appendChild(mapElement);

            setTimeout(function() {
                getMapProvider().triggerResize();
            }.bind(this));
        },

        clearMarkers: function() {
            getMapProvider() && getMapProvider().cleanMarkers();
        },

        addMarker: function(lng, lat) {
            getMapProvider() && getMapProvider().addMarker(lng, lat);
        },

        moveTo: function(lng, lat) {
            getMapProvider() && getMapProvider().moveTo(lng, lat);
        }
    };
});