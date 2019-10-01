window.addEventListener('DOMContentLoaded', (event) => {
    var el = document.querySelector('[data-locations]');
    var map = new google.maps.Map(el, {
        center: {
            lat: -34.397,
            lng: 150.644
        },
        zoom: 20
    });
    var coords = el.dataset.locations;

    for (var i = 0; i < coords.length; i++) {
        var latLng = new google.maps.LatLng(coords[1],coords[0]);
        var marker = new google.maps.Marker({
            position: latLng,
            map: map
        });
    }
});
