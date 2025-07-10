

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Info windows</title>
    <style>
    /* Always set the map height explicitly to define the size of the div
    * element that contains the map. */
    #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    </style>
</head>
<body>
    <div id="map"></div>
    <script>
        var locations = {!! json_encode($map) !!};

        // When the user clicks the marker, an info window opens.

        function initMap() {
            var myLatLng = {lat: 22.308096, lng: 73.165162};

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: new google.maps.LatLng(locations[0][1], locations[0][2]),
            });

            var count=0;

            for (count = 0; count < locations.length; count++) {  

                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[count][1], locations[count][2]),
                    label: ""+(count+1),
                    map: map,
                    // icon: "",
                });

                marker.info = new google.maps.InfoWindow({
                    content: locations [count][0]
                });

                google.maps.event.addListener(marker, 'click', function() {  
                    // this = marker
                    var marker_map = this.getMap();
                    this.info.open(marker_map, this);
                    // Note: If you call open() without passing a marker, the InfoWindow will use the position specified upon construction through the InfoWindowOptions object literal.
                });
            }
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('MAPLINK') }}&callback=initMap"></script>
</body>
</html>