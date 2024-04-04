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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$selectedPlantID = $_POST['selectedPlant'];
}

$query = "SELECT Temperature, Humidity FROM measurements
          WHERE PlantID = $selectedPlantID
          ORDER BY Timestamp DESC
          LIMIT 1";

$result = $conn->query($query);

if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	$temperature = $row["Temperature"];
	$humidity = $row["Humidity"];
} else {
	$temperature = "N/A";
	$humidity = "N/A";
}

$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
	<title>Home</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
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
	<div class="space"></div>
	<div class="container">
		<!-- Temperature Card -->
		<div class="card">
			<div class="card-icon"><i class="fa fa-thermometer" aria-hidden="true"></i></div>
			<div class="card-name">Soil Temperature</div>
			<div class="card-text">
				<?php echo $temperature; ?>°C
			</div>
			<a href="temp.php?selectedPlantID=<?php echo urlencode($selectedPlantID); ?>" class="details-button">See
				Details</a>
		</div>

		<!-- Humidity Card -->
		<div class="card">
			<div class="card-icon"><i class="fa fa-tint" aria-hidden="true"></i></div>
			<div class="card-name">Humidity</div>
			<div class="card-text">
				<?php echo $humidity; ?>%
			</div>
			<button class="details-button">See Details</button>
		</div>

		<!-- PH Quality Card -->
		<div class="card">
			<div class="card-icon"><i class="fa fa-flask" aria-hidden="true"></i></div>
			<div class="card-name">PH Quality</div>
			<div class="card-text" id="phQuality">pH 7</div>
			<button class="details-button">See Details</button>
		</div>

		<!-- Soil Moisture Card -->
		<div class="card">
			<div class="card-icon"><i class="fa fa-pagelines" aria-hidden="true"></i></div>
			<div class="card-name">Soil Moisture</div>
			<div class="card-text" id="soilmoisture">50%</div>
			<button class="details-button">See Details</button>
		</div>

		<!-- Light Card -->
		<div class="card">
			<div class="card-icon"><i class="fa fa-sun-o" aria-hidden="true"></i></div>
			<div class="card-name">Light</div>
			<div class="card-text" id="Light">1000Lux</div>
			<button class="details-button">See Details</button>
		</div>

		<!-- Schedule Watering Card -->
		<div class="card">
			<div class="card-icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
			<div class="card-name">Schedule Watering</div>
			<div class="water-scheduler">
				<input type="datetime-local" id="wateringTime">
				<button class="details-button" id="scheduleButton">Set Schedule</button>
			</div>
		</div>
	</div>

</body>

</html>