<?php
session_start();
$uname = $_SESSION['username'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iot";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$selectedPlantID = '';
if (isset($_GET['selectedPlantID'])) {
    $selectedPlantID = $_GET['selectedPlantID'];
} else {
    echo "No plant ID provided.";
    exit;
}

$query = "SELECT Temperature, Timestamp FROM measurements WHERE PlantID = $selectedPlantID ORDER BY Timestamp ASC";

$result = $conn->query($query);

// Fetch the data into an associative array
$measurements = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html>
<style>
    #temperatureChart {
        display: block;
        background-color: white;
        overflow: hidden;
    }
</style>

<head>
    <title>Select Plant</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg">
    <div class="header">
        <ul>
            <h1>Plant Monitoring System</h1>
        </ul>
        <div class="user_info">
            Logged in as
            <?php echo "$uname"; ?>
            <a class="details-button" href="logout.php">Logout</a>
        </div>
    </div>
    <div id="temperatureCard">
        <h2>Temperature</h2>
    </div>
    <div id="chartContainer">
        <canvas id="temperatureChart"></canvas>
    </div>
</body>

</html>
<script>
    var temperatureData = <?php echo json_encode(array_column($measurements, 'Temperature')); ?>;
    var timestamps = <?php echo json_encode(array_column($measurements, 'Timestamp')); ?>;

    window.onload = function () {
        document.getElementById('chartContainer').style.display = 'block';

        var ctx = document.getElementById('temperatureChart').getContext('2d');
        var temperatureChart = new Chart(ctx, {
            type: 'line', // Change this to 'bar' or other types if needed
            data: {
                labels: timestamps,
                datasets: [{
                    label: 'Temperature',
                    data: temperatureData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                }
            }
        });
    };
</script>