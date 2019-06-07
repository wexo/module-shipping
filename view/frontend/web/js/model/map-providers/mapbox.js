define([
    'mapboxgl',
    './abstract-map-provider'
], function(MapBox, Class) {

    MapBox.accessToken = 'pk.eyJ1IjoidGhlZmpvbmciLCJhIjoiY2p3ZTJ0eG14MTVvOTN6bHphMmx5aDBoZyJ9.c-hrEr20nY4D0YRFip7VPg';
    return Class.extend({
        defaults: {
            mapInstance: null
        },

        triggerResize: function() {
            this.mapInstance && this.mapInstance.resize();
        },

        cleanMarkers: function() {
            this.markers.forEach(function(marker) {
                marker.remove();
            });
        },

        /**
         * @param lng
         * @param lat
         */
        addMarker: function(lng, lat) {
            if (this.mapInstance) {
                var marker = new MapBox.Marker()
                    .setLngLat([lng, lat])
                    .addTo(this.mapInstance);
                this.markers.push(marker);
            }
        },

        /**
         * @param lng
         * @param lat
         */
        moveTo: function(lng, lat) {
            this.mapInstance && this.mapInstance.panTo([lng, lat]);
        },

        create: function() {
            if (!this.mapInstance) {
                this.mapInstance =
                    new MapBox.Map({
                        container: this.element,
                        zoom: 13,
                        style: 'mapbox://styles/mapbox/light-v9',
                        interactive: false
                    });
            }
        },

        isAllowed: function() {
            return MapBox.supported();
        }
    });
});
