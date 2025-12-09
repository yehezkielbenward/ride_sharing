  <?php
  include 'config.php';  // Pastikan config.php sudah ada untuk koneksi

  // Script SQL untuk membuat tabel (dengan IF NOT EXISTS untuk menghindari error jika sudah ada)
  $sql = "
  CREATE TABLE IF NOT EXISTS users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nim_nip VARCHAR(20) UNIQUE NOT NULL,
      name VARCHAR(100) NOT NULL,
      email VARCHAR(100) UNIQUE NOT NULL,
      password VARCHAR(255) NOT NULL,
      role ENUM('driver', 'passenger') NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE IF NOT EXISTS routes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      driver_id INT NOT NULL,
      origin VARCHAR(100) NOT NULL,
      destination VARCHAR(100) NOT NULL,
      departure_time TIME NOT NULL,
      available_seats INT NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE CASCADE
  );

  CREATE TABLE IF NOT EXISTS ride_requests (
      id INT AUTO_INCREMENT PRIMARY KEY,
      passenger_id INT NOT NULL,
      route_id INT NOT NULL,
      status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
      requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (passenger_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE
  );

  CREATE TABLE IF NOT EXISTS messages (
      id INT AUTO_INCREMENT PRIMARY KEY,
      sender_id INT NOT NULL,
      receiver_id INT NOT NULL,
      message TEXT NOT NULL,
      sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
  );

  CREATE TABLE IF NOT EXISTS ratings (
      id INT AUTO_INCREMENT PRIMARY KEY,
      reviewer_id INT NOT NULL,
      reviewed_id INT NOT NULL,
      ride_request_id INT NOT NULL,
      rating INT CHECK (rating >= 1 AND rating <= 5),
      review TEXT,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (reviewed_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (ride_request_id) REFERENCES ride_requests(id) ON DELETE CASCADE
  );
  ";

  try {
      // Jalankan script SQL
      $pdo->exec($sql);
      echo "<h2>Setup Berhasil!</h2>";
      echo "<p>Semua tabel database telah dibuat. Anda sekarang bisa mengakses aplikasi.</p>";
      echo "<a href='index.php'>Lanjut ke Aplikasi</a>";
  } catch (PDOException $e) {
      echo "<h2>Error Setup:</h2>";
      echo "<p>" . $e->getMessage() . "</p>";
      echo "<p>Pastikan database 'ride_sharing' sudah dibuat dan koneksi MySQL berjalan.</p>";
  }
  ?>
  