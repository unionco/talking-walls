window.addEventListener('DOMContentLoaded', (event) => {
    var murals = document.querySelector('[data-murals]');
    if (murals) {
        var items = murals.querySelectorAll("[data-murals-list-item]");
        var currentYear = "2019";
        var mapEl = murals.querySelector('[data-murals-map]');
        var switchButton = murals.querySelector('[data-murals-switch]');
        var bounds = new google.maps.LatLngBounds();
        var map = new google.maps.Map(mapEl, {
            zoom: 13,
            styles: mapStyle,
            zoomControl: true,
            zoomControlOptions: {
                position: google.maps.ControlPosition.TOP_LEFT
            },
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true
        });
        var data = JSON.parse(murals.dataset.murals);
        var infoWindow = new google.maps.InfoWindow({maxWidth: 'unset'});

        var markerIcon = {
            path: "M 15,15 m -10, 0 a 10,10 0 1,0 20,0 a 10,10 0 1,0 -20,0",
            fillColor: '#000000',
            fillOpacity: 1,
            anchor: new google.maps.Point(0, 0),
            strokeColor: '#ffffff',
            strokeWeight: 2,
            scale: 1
        }

        function addMarker(location, map, markerData) {
            // console.log(markerData);
            // Add the marker at the clicked location, and add the next-available label
            // from the array of alphabetical characters.
            markerIcon.fillColor = markerData.year.slug === currentYear ? '#6600ff' : '#000000';

            var marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: markerIcon
            });

            var muralHTML = document.querySelector('[data-murals-list-item="' + markerData.ID + '"]').innerHTML;

            // infoWindow.querySelector('button').innerHTML = '&times; CLOSE'

            marker.addListener('click', function() {
                infoWindow.setContent(muralHTML)
                infoWindow.open(map, marker);
            });

            google.maps.event.addListener(map, 'click', function() {
                infoWindow.close();
            });

            bounds.extend(location);
            map.fitBounds(bounds);

            return marker;
        }

        // Add markers to map
        var markers = [];
        for (var i = 0; i < data.length; i++) {
            var location = data[i].location;
            var latLng = new google.maps.LatLng(location.lat, location.lng);
            markers.push(Object.assign({}, { id: data[i].ID }, { marker: addMarker(latLng, map, data[i]) }));
        }

        console.log(markers);

        for (var i = 0; i < items.length; i++) {
            const item = items[i];
            item.addEventListener('mouseenter', function() {
                // find marker
                const id = +item.getAttribute('data-murals-list-item');
                const marker = markers.filter(function(m) {
                    return m.id === id;
                })[0];
                marker.marker.setAnimation(google.maps.Animation.BOUNCE);

                setTimeout(() => {
                    marker.marker.setAnimation(null);
                }, 700);
            });
        }

        window.addEventListener('resize', function() {
            map.fitBounds(bounds);
        });

        switchButton.addEventListener('click', function() {
            murals.classList.toggle('is-map-active');
            switchButton.dataset.muralsSwitch = murals.classList.contains('is-map-active') ? 'List View' : 'Map View';
            map.fitBounds(bounds);
            map.setZoom(map.getZoom() - 2);
            murals.scrollIntoView({behavior: 'smooth'})
        })
    }
});

var mapStyle = [
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#333333"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#fefefe"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#d5d5d5"
            }
        ]
    },
    {
        "featureType": "landscape.man_made",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#7574c0"
            },
            {
                "saturation": "-37"
            },
            {
                "lightness": "75"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f5f5f5"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "poi.business",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#7574c0"
            },
            {
                "saturation": "-2"
            },
            {
                "lightness": "53"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#dedede"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#7574c0"
            },
            {
                "lightness": "69"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#7574c0"
            },
            {
                "lightness": "25"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "lightness": "38"
            },
            {
                "color": "#000000"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#ffffff"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#f2f2f2"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e9e9e9"
            },
            {
                "lightness": 17
            }
        ]
    }
]
