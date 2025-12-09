<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'driver') {
    header("Location: dashboard.php");
    exit();
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $available_seats = $_POST['available_seats'];

    $stmt = $pdo->prepare("INSERT INTO routes (driver_id, origin, destination, departure_time, available_seats) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $origin, $destination, $departure_time, $available_seats]);
    echo "Route added!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Route</title></head>
<body>
    <form method="POST">
        Origin: <input type="text" name="origin" required><br>
        Destination: <input type="text" name="destination" required><br>
        Departure Time: <input type="time" name="departure_time" required><br>
        Available Seats: <input type="number" name="available_seats" required><br>
        <button type="submit">Add Route</button>
    </form>
    <a href="dashboard.php">Back</a>
</body>
</html>