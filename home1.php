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

$query = "SELECT PlantID, Name FROM Plants WHERE UserID = (
    SELECT UserID FROM Users WHERE Username = '$uname'
)";
$result = $conn->query($query);
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Select Plant</title>
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
    <div class="container1">
        <h2>Select a Plant</h2>
        <div class="select-box">
            <form method="post" action="home.php">
                <select name="selectedPlant">
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $plantID = $row["PlantID"];
                        $plantName = $row["Name"];
                        echo "<option value='$plantID'>$plantName</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Select">
            </form>
        </div>
    </div>
</body>

</html>