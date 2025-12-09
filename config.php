<?php
$host = 'localhost';
$dbname = 'ride_sharing';
$username = 'root';  // Default XAMPP
$password = '';      // Default XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>