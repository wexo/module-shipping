define([
    './mapbox',
    './leaflet'
], function(MapBox, Leaflet) {

    return {
        mapBox: MapBox,
        leaflet: Leaflet
    }
});