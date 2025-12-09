<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: dashboard.php");
    exit();
}
include 'config.php';

if (isset($_GET['route_id'])) {
    $route_id = $_GET['route_id'];
    $stmt = $pdo->prepare("INSERT INTO ride_requests (passenger_id, route_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $route_id]);
    echo "Request sent!";
}
header("Location: search_routes.php");
?>