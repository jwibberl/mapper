<?php
require 'config.php';

$coordinates = [];
$colors = []; // Array to store colors for each line segment

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        $header = fgetcsv($handle);
        $gpsIndex = array_search("GPS", $header);
        $trssIndex = array_search("TRSS(dB)", $header); // Index for the TRSS(dB) column

        if ($gpsIndex === false || $trssIndex === false) {
            die("CSV does not contain 'GPS' or 'TRSS(dB)' columns.");
        }

        while (($row = fgetcsv($handle)) !== FALSE) {
            // Split the GPS coordinates by a space
            $gps = explode(' ', $row[$gpsIndex]);
            if(count($gps) == 2){
                $lat = floatval(trim($gps[0]));
                $lng = floatval(trim($gps[1]));
                $trss = floatval($row[$trssIndex]); // Get the TRSS(dB) value

                // Assign color based on TRSS(dB) value
                if ($trss <= -40) {
                    $color = 'green'; // Strong signal
                } elseif ($trss > -65 && $trss <= -90) {
                    $color = 'orange'; // Moderate signal
                } else {
                    $color = 'red'; // Weak signal
                }

                $coordinates[] = ["lat" => $lat, "lng" => $lng];
                $colors[] = $color; // Store the color for this point
            }
        }
        fclose($handle);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSV GPS Plotter</title>
    <style>
        #map { height: 500px; width: 100%; margin-top: 20px; }
        body { font-family: Arial, sans-serif; padding: 20px; }
    </style>
</head>
<body>
    <h2>Upload CSV with 'GPS' and 'TRSS(dB)' columns</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit">Upload & Plot</button>
    </form>

    <?php if (!empty($coordinates)): ?>
        <div id="map"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY ?>">></script>
        <script>
            const coordinates = <?php echo json_encode($coordinates); ?>;
            const colors = <?php echo json_encode($colors); ?>;

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: coordinates[0] // Center on the first point
            });

            // Loop through the coordinates and plot each segment with the corresponding color
            for (let i = 0; i < coordinates.length - 1; i++) {
                const path = [coordinates[i], coordinates[i + 1]]; // Segment between two points
                const color = colors[i]; // Color based on TRSS(dB)

                const flightPath = new google.maps.Polyline({
                    path: path,
                    geodesic: true,
                    strokeColor: color,  // Set the color for the segment
                    strokeOpacity: 1.0,
                    strokeWeight: 4  // You can adjust the weight to make the line thicker
                });

                flightPath.setMap(map); // Add the segment to the map
            }
        </script>
    <?php endif; ?>
</body>
</html>
