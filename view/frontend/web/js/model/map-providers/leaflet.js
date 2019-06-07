define([
    './abstract-map-provider',
    'Wexo_Shipping/lib/leaflet'
], function(Class, Leaflet) {

    return Class.extend({
        defaults: {
            mapInstance: null,
            tileUrl: 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png',
            zoom: 13
        },

        triggerResize: function() {
            this.mapInstance && this.mapInstance.invalidateSize();
        },

        cleanMarkers: function() {
            this.markers.forEach(function(marker) {
                marker.remove();
            });
        },

        addMarker: function(lng, lat) {
            this.markers.push(
                Leaflet.marker([lat, lng]).addTo(this.mapInstance)
            );
        },

        moveTo: function(lng, lat) {
            this.mapInstance && this.mapInstance.panTo([lat, lng]);
        },

        create: function() {
            if (!this.mapInstance) {
                this.mapInstance = Leaflet.map(this.element, {
                    center: [0, 0],
                    zoom: this.zoom,
                    pan: true,
                    attributionControl: false,
                    zoomControl: false,
                    preferCanvas: true,
                    trackResize: true,
                    boxZoom: false,
                    dragging: false,
                    doubleClickZoom: false,
                    keyboard: false,
                    scrollWheelZoom: false,
                    touchZoom: false,
                    tap: false
                });
                Leaflet.tileLayer(this.tileUrl).addTo(this.mapInstance);
            }
        },

        /**
         * Is allowed to view map
         */
        isAllowed: function() {
            return true;
        }
    });
});