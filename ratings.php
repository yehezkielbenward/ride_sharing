<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviewed_id = $_POST['reviewed_id'];
    $ride_request_id = $_POST['ride_request_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $pdo->prepare("INSERT INTO ratings (reviewer_id, reviewed_id, ride_request_id, rating, review) VALUES (?, ?, ?, ?, ?)")->execute([$user_id, $reviewed_id, $ride_request_id, $rating, $review]);
    echo "<p style='color: #4caf50; font-weight: bold;'>Rating submitted! ✅</p>";
}

// Tampilkan rating yang diterima (contoh sederhana)
$ratings = $pdo->prepare("SELECT * FROM ratings WHERE reviewed_id = ?");
$ratings->execute([$user_id]);
$ratings = $ratings->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ratings - Ride Sharing</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e8f5e8; /* Hijau pastel lembut */
            color: #2e7d32; /* Hijau gelap untuk teks */
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h2, h3 {
            color: #4caf50; /* Hijau medium */
            font-size: 2em;
            margin-bottom: 20px;
        }
        .rating-card {
            background-color: #c8e6c9; /* Hijau lebih terang */
            padding: 15px;
            margin: 10px auto;
            border-radius: 10px; /* Sudut bulat */
            width: 80%;
            max-width: 600px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .rating-card p {
            margin: 5px 0;
        }
        form {
            background-color: #c8e6c9;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-bottom: 30px;
            width: 80%;
            max-width: 400px;
        }
        input[type="number"], textarea {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #66bb6a;
            border-radius: 10px;
            font-size: 1em;
        }
        textarea {
            height: 80px;
            resize: vertical;
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
        .no-ratings {
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
    <h2>⭐ Your Ratings ⭐</h2>
    <p>Lihat ulasan dari pengguna lain! 🌟💚</p>
    
    <?php if (count($ratings) > 0): ?>
        <?php foreach ($ratings as $rate): ?>
            <div class="rating-card">
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($rate['rating']); ?>/5 ⭐</p>
                <p><strong>Review:</strong> <?php echo htmlspecialchars($rate['review']); ?> 💬</p>
                <p><small>Reviewed on: <?php echo htmlspecialchars($rate['created_at']); ?> 📅</small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-ratings">Belum ada rating untukmu. Tetap semangat! 😊</p>
    <?php endif; ?>
    
    <h3>Submit a Rating <span class="emoji">📝</span></h3>
    <form method="POST">
        <label for="reviewed_id">Reviewed User ID <span class="emoji">👤</span>:</label><br>
        <input type="number" name="reviewed_id" id="reviewed_id" placeholder="e.g., 2" required><br>
        
        <label for="ride_request_id">Ride Request ID <span class="emoji">🚗</span>:</label><br>
        <input type="number" name="ride_request_id" id="ride_request_id" placeholder="e.g., 1" required><br>
        
        <label for="rating">Rating (1-5) <span class="emoji">⭐</span>:</label><br>
        <input type="number" name="rating" id="rating" min="1" max="5" placeholder="1-5" required><br>
        
        <label for="review">Review <span class="emoji">💬</span>:</label><br>
        <textarea name="review" id="review" placeholder="Tulis ulasanmu di sini..."></textarea><br>
        
        <button type="submit">Submit Rating <span class="emoji">✅</span></button>
    </form>
    
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
