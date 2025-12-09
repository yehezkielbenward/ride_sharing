<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

$user_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'] ?? null;  // ID penerima dari URL

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $receiver_id) {
    $message = $_POST['message'];
    $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)")->execute([$user_id, $receiver_id, $message]);
    // Redirect untuk refresh pesan
    header("Location: chat.php?receiver_id=$receiver_id");
    exit();
}

$messages = [];
if ($receiver_id) {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at");
    $stmt->execute([$user_id, $receiver_id, $receiver_id, $user_id]);
    $messages = $stmt->fetchAll();
}

// Ambil list user lain untuk dropdown (kecuali diri sendiri)
$users = $pdo->prepare("SELECT id, name FROM users WHERE id != ?");
$users->execute([$user_id]);
$users = $users->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat - Ride Sharing</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e8f5e8; /* Hijau pastel lembut */
            color: #2e7d32; /* Hijau gelap untuk teks */
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h2 {
            color: #4caf50; /* Hijau medium */
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .chat-container {
            background-color: #c8e6c9; /* Hijau lebih terang */
            padding: 20px;
            border-radius: 15px; /* Sudut bulat */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 80%;
            max-width: 600px;
            margin-bottom: 20px;
        }
        .messages {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f1f8e9; /* Hijau sangat terang */
            border-radius: 10px;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 15px;
            max-width: 70%;
            word-wrap: break-word;
        }
        .message.you {
            background-color: #66bb6a; /* Hijau untuk pesan Anda */
            color: white;
            margin-left: auto;
            text-align: right;
        }
        .message.them {
            background-color: #a5d6a7; /* Hijau lebih muda untuk pesan mereka */
            color: #2e7d32;
            margin-right: auto;
            text-align: left;
        }
        .message small {
            display: block;
            font-size: 0.8em;
            opacity: 0.8;
        }
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 2px solid #66bb6a;
            border-radius: 20px;
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
        select {
            padding: 10px;
            border: 2px solid #66bb6a;
            border-radius: 10px;
            font-size: 1em;
            margin-bottom: 20px;
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
        .emoji {
            font-size: 1.2em;
        }
        .no-chat {
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h2>💬 Chat 💬</h2>
    <p>Yuk, ngobrol bareng pengguna lain! 🗣️💚</p>
    
    <?php if (!$receiver_id): ?>
        <div class="chat-container">
            <p class="no-chat">Pilih pengguna untuk mulai chat! 👇</p>
            <form method="GET">
                <select name="receiver_id" required onchange="this.form.submit()">
                    <option value="">-- Pilih Pengguna --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']); ?> 👤</option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    <?php else: ?>
        <div class="chat-container">
            <div class="messages">
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?php echo $msg['sender_id'] == $user_id ? 'you' : 'them'; ?>">
                            <strong><?php echo $msg['sender_id'] == $user_id ? 'You' : 'Them'; ?>:</strong> <?php echo htmlspecialchars($msg['message']); ?>
                            <small>(<?php echo htmlspecialchars($msg['sent_at']); ?>)</small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-chat">Belum ada pesan. Mulai chat pertama! 😊</p>
                <?php endif; ?>
            </div>
            <form method="POST">
                <input type="text" name="message" placeholder="Ketik pesanmu di sini... 💬" required>
                <button type="submit">Send <span class="emoji">📤</span></button>
            </form>
        </div>
    <?php endif; ?>
    
    <a href="dashboard.php">⬅️ Back to Dashboard</a>
</body>
</html>
