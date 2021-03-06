jQuery(document).ready(function ($) {

    var icon1 = THEME_URL + '/images/Orcas-Island-Car.png';
    var icon2 = THEME_URL + '/images/Orcas-Island-Car.png';

    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var map;
        var mapStyles = [
            {
                "featureType": "administrative",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#3F404A"
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [
                    {
                        "color": "#D9F1FA"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 45
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [
                    {
                        "color": "#6BC5D3"
                    },
                    {
                        "visibility": "on"
                    }
                ]
            }
        ];
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap',
            scrollwheel: false,
            styles: mapStyles
        };

        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        map.setTilt(45);

        var markers = [["<div class=\"stm_offices_wrapper with-map\"><div class=\"location heading-font\">Rosario Resort" +
        "<\/div>" +
            "<div class=\"address\">" +
            "<i class=\"stm-icon-pin\"><\/i>Rosario Resort, Rosario Rd, Eastsound, WA 98245" +
            "<\/div>" +
            "<\/div>", "48.644669", "-122.873301", 0, "Rosario Resort", 107],
            ["<div class=\"stm_offices_wrapper with-map\"><div class=\"location heading-font\">Orcas Island Ferry" +
            "<\/div>" +
            "<div class=\"address\">" +
            "<i class=\"stm-icon-pin\"><\/i>Orcas Island Ferry Terminal, Orcas Road, Orcas, WA" +
            "<\/div>" +
            "<\/div>", "48.597394", "-122.943642", 1, "Orcas Island Ferry", 98],
            ["<div class=\"stm_offices_wrapper with-map\">" +
            "<div class=\"location heading-font\">Eastsound \/ Airport" +
            "<\/div>" +
            "<div class=\"address\"><i class=\"stm-icon-pin\">" +
            "<\/i>Eastsound, WA" +
            "<\/div>" +
            "<\/div>", "48.696654", "-122.906107", 2, "Eastsound \/ Airport", 99],
            ["<div class=\"stm_offices_wrapper with-map\">" +
            "<div class=\"location heading-font\">Inn at Ship Bay" +
            "<\/div>" +
            "<div class=\"address\">" +
            "<i class=\"stm-icon-pin\"><\/i>26 Olga Rd, Eastsound, WA" +
            "<\/div>" +
            "<\/div>", "48.691039", "-122.884262", 3, "Inn at Ship Bay", 815],
            ["<div class=\"stm_offices_wrapper with-map\"><div class=\"location heading-font\">Deer Harbor" +
            "<\/div><div class=\"address\"><i class=\"stm-icon-pin\"><\/i>Deer Harbor Rd, Deer Harbor, WA 98243" +
            "<\/div><\/div>", "48.620049", "-123.002760", 4, "Deer Harbor", 689],
            ["<div class=\"stm_offices_wrapper with-map\"><div class=\"location heading-font\">West Sound Marina<\/div><\/div>", "48.629404", "-122.956760", 5, "West Sound Marina", 837]];

        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;

        // Loop through our array of markers & place each one on the map
        for (i = 0; i < markers.length; i++) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: markers[i][0],
                icon: icon1
            });

            google.maps.event.addListener(marker, 'mouseover', function () {
                this.setIcon(icon2);
            });
            google.maps.event.addListener(marker, 'mouseout', function () {
                this.setIcon(icon1);
            });

            // Allow each marker to have an info window
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infoWindow.setContent(markers[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));

            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }

        var timeOut;
        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
            window.clearTimeout(timeOut);
            timeOut = window.setTimeout(function () {
                map.setZoom(11);
            }, 400);

            google.maps.event.removeListener(boundsListener);
        });

        google.maps.event.addListener(infoWindow, 'domready', function () {

            var iwOuter = $('.gm-style-iw');
            var iwBackground = iwOuter.prev();
            iwBackground.addClass('stm-iw-wrapper');
            iwBackground.children(':nth-child(1)').addClass('stm-iw-first');
            iwBackground.children(':nth-child(2)').addClass('stm-iw-second');
            iwBackground.children(':nth-child(3)').addClass('stm-iw-third');
            iwBackground.children(':nth-child(4)').addClass('stm-iw-fourth');

        });
    }
});