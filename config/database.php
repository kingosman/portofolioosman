<?php
session_start();

$host = 'sql213.infinityfree.com';
$user = 'if0_41390389';
$pass = 'osmannc07'; // Adjust to your MySQL root password if not empty
$db   = 'if0_41390389_portofolio_osman';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper to get settings
function getSetting($conn, $keyName) {
    $stmt = $conn->prepare("SELECT value FROM settings WHERE key_name = ?");
    $stmt->bind_param("s", $keyName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['value'];
    }
    return '';
}
