<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Ride Sharing</title>
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
        .welcome {
            background-color: #c8e6c9; /* Hijau lebih terang */
            padding: 20px;
            border-radius: 15px; /* Sudut bulat untuk kesan gemes */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-bottom: 30px;
        }
        a {
            display: inline-block;
            background-color: #66bb6a; /* Hijau button */
            color: white;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 25px; /* Bulat banget */
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #43a047; /* Hijau lebih gelap saat hover */
            transform: scale(1.05); /* Efek zoom kecil untuk gemes */
        }
        .links {
            margin-top: 20px;
        }
        .emoji {
            font-size: 1.2em; /* Emoji lebih besar */
        }
    </style>
</head>
<body>
    <div class="welcome">
        <h1>🌿 Welcome, <?php echo ucfirst($role); ?>! 🌿</h1>
        <p>Yuk, mulai petualangan ride-sharing kamu! 🚗💚</p>
    </div>
    
    <div class="links">
        <?php if ($role == 'driver'): ?>
            <a href="add_route.php">➕ Add Route <span class="emoji">🛣️</span></a>
            <a href="view_requests.php">📋 View Requests <span class="emoji">👀</span></a>
        <?php else: ?>
            <a href="search_routes.php">🔍 Search Routes <span class="emoji">🗺️</span></a>
            <a href="my_requests.php">📝 My Requests <span class="emoji">📄</span></a>
        <?php endif; ?>
        <a href="chat.php">💬 Chat <span class="emoji">🗣️</span></a>
        <a href="ratings.php">⭐ Ratings <span class="emoji">🌟</span></a>
        <a href="logout.php">🚪 Logout <span class="emoji">👋</span></a>
    </div>
</body>
</html>
