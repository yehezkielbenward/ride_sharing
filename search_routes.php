<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'passenger') {
    header("Location: dashboard.php");
    exit();
}
include 'config.php';

$routes = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $time = $_POST['time'];

    // Pencocokan otomatis: Cari rute searah berdasarkan waktu dan lokasi
    $stmt = $pdo->prepare("SELECT r.*, u.name FROM routes r JOIN users u ON r.driver_id = u.id WHERE r.origin LIKE ? AND r.destination LIKE ? AND r.departure_time >= ?");
    $stmt->execute(["%$origin%", "%$destination%", $time]);
    $routes = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Routes - Ride Sharing</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e8f5e8; /* Hijau pastel lembut */
            color: #2e7d32; /* Hijau gelap untuk teks */
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #4caf50; /* Hijau medium */
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        form {
            background-color: #c8e6c9; /* Hijau lebih terang */
            padding: 20px;
            border-radius: 15px; /* Sudut bulat */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-bottom: 30px;
            width: 80%;
            max-width: 400px;
        }
        input[type="text"], input[type="time"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #66bb6a;
            border-radius: 10px;
            font-size: 1em;
        }
        button {
            background-color: #66bb6a; /* Hijau button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px; /* Bulat banget */
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #43a047; /* Hijau lebih gelap saat hover */
            transform: scale(1.05); /* Efek zoom kecil */
        }
        .route {
            background-color: #c8e6c9;
            padding: 15px;
            margin: 10px auto;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .route p {
            margin: 5px 0;
        }
        a {
            display: inline-block;
            background-color: #66bb6a;
            color: white;
            padding: 8px 15px;
            margin: 5px;
            text-decoration: none;
            border-radius: 20px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #43a047;
            transform: scale(1.05);
        }
        .no-results {
            color: #666;
            font-style: italic;
            margin-top: 20px;
        }
        .emoji {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1>🔍 Search Routes 🔍</h1>
    <p>Yuk, cari rute impianmu! 🗺️💚</p>
    
    <form method="POST">
        <label for="origin">Origin <span class="emoji">🏠</span>:</label><br>
        <input type="text" name="origin" id="origin" placeholder="e.g., Jakarta" required><br>
        
        <label for="destination">Destination <span class="emoji">🎯</span>:</label><br>
        <input type="text" name="destination" id="destination" placeholder="e.g., Bandung" required><br>
        
        <label for="time">Departure Time <span class="emoji">⏰</span>:</label><br>
        <input type="time" name="time" id="time" required><br>
        
        <button type="submit">Search <span class="emoji">🔎</span></button>
    </form>
    
    <?php if (count($routes) > 0): ?>
        <?php foreach ($routes as $route): ?>
            <div class="route">
                <p><strong>Driver:</strong> <?php echo htmlspecialchars($route['name']); ?> 🚗</p>
                <p><strong>Origin:</strong> <?php echo htmlspecialchars($route['origin']); ?> 🏠</p>
                <p><strong>Destination:</strong> <?php echo htmlspecialchars($route['destination']); ?> 🎯</p>
                <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($route['departure_time']); ?> ⏰</p>
                <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($route['available_seats']); ?> 💺</p>
                <a href="request_ride.php?route_id=<?php echo $route['id']; ?>">Request Ride <span class="emoji">🙋</span></a>
            </div>
        <?php endforeach; ?>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <p class="no-results">Tidak ada rute yang cocok. Coba ubah pencarian! 😔</p>
    <?php endif; ?>
    
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
