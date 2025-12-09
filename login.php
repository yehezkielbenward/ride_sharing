<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim_nip = $_POST['nim_nip'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE nim_nip = ?");
    $stmt->execute([$nim_nip]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
    } else {
        echo "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9); /* Hijau pastel lembut */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #2e7d32; /* Hijau gelap untuk teks */
        }
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #4caf50; /* Hijau pastel medium */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #388e3c; /* Hijau gelap */
        }
        input[type="text"], input[type="password"] {
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #a5d6a7; /* Border hijau pastel */
            border-radius: 10px;
            font-size: 1em;
            background: #f1f8e9; /* Background input hijau sangat lembut */
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #66bb6a; /* Hijau lebih terang saat focus */
            outline: none;
        }
        button {
            padding: 15px;
            background: linear-gradient(135deg, #81c784, #4caf50); /* Gradient hijau pastel */
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-bottom: 20px;
        }
        button:hover {
            background: linear-gradient(135deg, #66bb6a, #388e3c);
            transform: translateY(-2px);
        }
        a {
            color: #4caf50;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #2e7d32;
            text-decoration: underline;
        }
        .error {
            color: #d32f2f; /* Merah untuk error */
            margin-bottom: 20px;
            font-weight: bold;
        }
        .decorative {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 3em;
            color: rgba(76, 175, 80, 0.2);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="decorative">🌿</div> <!-- Ikon dekoratif hijau -->
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <label for="nim_nip">NIM/NIP:</label>
            <input type="text" id="nim_nip" name="nim_nip" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
