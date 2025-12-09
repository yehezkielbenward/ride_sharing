<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim_nip = $_POST['nim_nip'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Validasi sederhana (tambahkan verifikasi kampus jika perlu)
    if (empty($nim_nip) || empty($name) || empty($email) || empty($password) || empty($role)) {
        echo "<p style='color: #d32f2f; text-align: center; font-weight: bold;'>All fields are required. ❌</p>";
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO users (nim_nip, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$nim_nip, $name, $email, $password, $role])) {
        header("Location: login.php");
        exit();
    } else {
        echo "<p style='color: #d32f2f; text-align: center; font-weight: bold;'>Registration failed. Try again. ❌</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Ride Sharing</title>
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
            padding: 30px;
            border-radius: 15px; /* Sudut bulat */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            width: 80%;
            max-width: 400px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            text-align: left;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #66bb6a;
            border-radius: 10px;
            font-size: 1em;
            box-sizing: border-box;
        }
        select {
            background-color: white;
        }
        button {
            background-color: #66bb6a; /* Hijau button */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px; /* Bulat banget */
            font-weight: bold;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        button:hover {
            background-color: #43a047; /* Hijau lebih gelap saat hover */
            transform: scale(1.05); /* Efek zoom kecil */
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #4caf50;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .emoji {
            font-size: 1.2em;
        }
        .welcome {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="welcome">
        <h1>🌿 Register 🌿</h1>
        <p>Daftar sekarang dan mulai petualangan ride-sharing! 🚗💚</p>
    </div>
    
    <form method="POST">
        <label for="nim_nip">NIM/NIP <span class="emoji">🎓</span>:</label>
        <input type="text" name="nim_nip" id="nim_nip" placeholder="Masukkan NIM/NIP kampus" required>
        
        <label for="name">Name <span class="emoji">👤</span>:</label>
        <input type="text" name="name" id="name" placeholder="Nama lengkap" required>
        
        <label for="email">Email <span class="emoji">📧</span>:</label>
        <input type="email" name="email" id="email" placeholder="email@example.com" required>
        
        <label for="password">Password <span class="emoji">🔒</span>:</label>
        <input type="password" name="password" id="password" placeholder="Kata sandi aman" required>
        
        <label for="role">Role <span class="emoji">🚗</span>:</label>
        <select name="role" id="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="driver">Driver 🛣️</option>
            <option value="passenger">Passenger 👋</option>
        </select>
        
        <button type="submit">Register <span class="emoji">✅</span></button>
    </form>
    
    <a href="login.php">Sudah punya akun? Login di sini! 🔑</a>
</body>
</html>
