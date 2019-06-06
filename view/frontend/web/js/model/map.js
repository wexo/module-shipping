define([
    'mapbox',
    'ko'
], function(MapBox, ko) {

    var mapElement = document.createElement('div');
    var mapInstance = ko.observable(null);
    var markers = [];

    return {
        mapInstance: mapInstance,
        element: mapElement,

        changeElement: function(element, height) {
            if (!mapInstance()) {
                this._createMapBox(element);
            }
            element.appendChild(mapElement);
            setTimeout(function() {
                mapInstance().resize();
            });
        },

        clearMarkers: function() {
            markers.forEach(function(marker) {
                marker.remove();
            });
        },

        _createMapBox: function() {
            mapInstance(
                new MapBox.Map({
                    container: mapElement,
                    zoom: 13,
                    style: 'mapbox://styles/mapbox/light-v9',
                    interactive: false
                })
            );
        },

        addMarker: function(lng, lat) {
            if (mapInstance()) {
                var marker = new MapBox.Marker()
                    .setLngLat([lng, lat])
                    .addTo(mapInstance());
                markers.push(marker);

                return marker;
            }
            return false;
        },

        moveTo: function(lng, lat) {
            if (mapInstance()) {
                mapInstance().panTo([lng, lat]);
                return true;
            }
            return false;
        }
    };
});