<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps Example</title>
    <style>
        /* Set the map size */
        #map {
            height: 100%;
            width: 100%;
        }
        /* Set the height of the body and html to full viewport height */
        html, body {
            height: 100%;
            margin: 0;
        }
    </style>
</head>
<body>

    <!-- Map container -->
    <div id="map"></div>

    <!-- Google Maps API Script -->
    <script>
        // Initialize the map
        function initMap() {
            // Map options
            const options = {
                center: { lat: 40.730610, lng: -73.935242 },  // New York City coordinates
                zoom: 12
            };

            // Create a new map
            const map = new google.maps.Map(document.getElementById("map"), options);

            // Add a marker
            const marker = new google.maps.Marker({
                position: { lat: 40.730610, lng: -73.935242 },
                map: map,
                title: "New York City"
            });
        }
    </script>

    <!-- Google Maps API (replace with your own API key) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAt-tJ1WfVfm4UxtbtKFStAE49leD4OoR4&callback=initMap" async defer></script>
</body>
</html>
