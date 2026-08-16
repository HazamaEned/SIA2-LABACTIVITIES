<?php
header('Content-Type: application/json');

// --- DB connection (adjust as needed) ---
$host = 'localhost';
$db   = 'sia_db';
$user = 'root';
$pass = 'admin';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// --- Get userid from query string ---
$userid = isset($_GET['userid']) ? (int) $_GET['userid'] : 0;

if ($userid <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing userid']);
    exit;
}

// --- Fetch name ---
$stmt = $conn->prepare('SELECT name FROM users WHERE userid = ?');
$stmt->bind_param('i', $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    http_response_code(200);
    echo json_encode(['userid' => $userid, 'name' => $row['name']]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
}

$stmt->close();
$conn->close();