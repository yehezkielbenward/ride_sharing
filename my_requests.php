<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: dashboard.php");
    exit();
}
include 'config.php';

$user_id = $_SESSION['user_id'];

// Ambil semua permintaan tumpangan dari passenger ini, dengan detail rute dan driver
$requests = $pdo->prepare("
    SELECT rr.id, rr.status, rr.requested_at, r.origin, r.destination, r.departure_time, u.name AS driver_name
    FROM ride_requests rr
    JOIN routes r ON rr.route_id = r.id
    JOIN users u ON r.driver_id = u.id
    WHERE rr.passenger_id = ?
    ORDER BY rr.requested_at DESC
");
$requests->execute([$user_id]);
$requests = $requests->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Requests - Ride Sharing</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e8f5e8; /* Hijau pastel */
            color: #2e7d32;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #4caf50;
            font-size: 2em;
        }
        .request {
            background-color: #c8e6c9;
            padding: 15px;
            margin: 10px auto;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .status {
            font-weight: bold;
            color: #388e3c; /* Hijau untuk status */
        }
        a {
            display: inline-block;
            background-color: #66bb6a;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #43a047;
            transform: scale(1.05);
        }
        .no-requests {
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>📝 My Ride Requests 📝</h1>
    <?php if (count($requests) > 0): ?>
        <?php foreach ($requests as $req): ?>
            <div class="request">
                <p><strong>Driver:</strong> <?php echo htmlspecialchars($req['driver_name']); ?> 🚗</p>
                <p><strong>Route:</strong> <?php echo htmlspecialchars($req['origin']); ?> to <?php echo htmlspecialchars($req['destination']); ?> 🛣️</p>
                <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($req['departure_time']); ?> ⏰</p>
                <p><strong>Status:</strong> <span class="status"><?php echo htmlspecialchars($req['status']); ?> ✅</span></p>
                <p><strong>Requested At:</strong> <?php echo htmlspecialchars($req['requested_at']); ?> 📅</p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-requests">Belum ada permintaan tumpangan. Cari rute dulu! 🔍</p>
    <?php endif; ?>
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
