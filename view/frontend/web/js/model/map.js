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


        changeElement: function(element) {
            if(!mapInstance()) {
                this._createMapBox(element)
            }
            element.appendChild(mapElement);
            mapElement.style.height = '300px';
            setTimeout(function() {
                mapInstance().resize();
            }, 100);
            console.log(mapInstance())
        },

        clearMarkers: function() {
            markers.forEach(function(marker) {
                marker.remove();
            })
        },

        _createMapBox: function() {
            mapInstance(
                  new MapBox.Map({
                      container: mapElement,
                      zoom: 13,
                      style: 'mapbox://styles/mapbox/light-v9'
                  })
            );
        },

        addMarker: function(lng, lat) {
            if(mapInstance()) {
                var marker = new MapBox.Marker()
                    .setLngLat([lng, lat])
                    .addTo(mapInstance());
                markers.push(marker);

                return marker;
            }
            return false;
        }
    }
});