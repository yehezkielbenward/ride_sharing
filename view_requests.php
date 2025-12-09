<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'driver') {
    header("Location: dashboard.php");
    exit();
}
include 'config.php';

$requests = $pdo->prepare("SELECT rr.*, u.name, r.origin, r.destination FROM ride_requests rr JOIN users u ON rr.passenger_id = u.id JOIN routes r ON rr.route_id = r.id WHERE r.driver_id = ?");
$requests->execute([$_SESSION['user_id']]);
$requests = $requests->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    $pdo->prepare("UPDATE ride_requests SET status = ? WHERE id = ?")->execute([$status, $request_id]);
    header("Location: view_requests.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>View Requests</title></head>
<body>
    <?php foreach ($requests as $req): ?>
        <p>Passenger: <?php echo $req['name']; ?> | Route: <?php echo $req['origin']; ?> to <?php echo $req['destination']; ?> | Status: <?php echo $req['status']; ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
            <button name="status" value="approved">Approve</button>
            <button name="status" value="rejected">Reject</button>
        </form></p>
    <?php endforeach; ?>
    <a href="dashboard.php">Back</a>
</body>
</html>