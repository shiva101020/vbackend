<?php
header('Content-Type: application/json');
include "config.php";

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// PostgreSQL prepared statement
$result = pg_query_params(
    $conn,
    "SELECT id, password FROM users WHERE email = $1",
    array($email)
);

if ($row = pg_fetch_assoc($result)) {
    if (password_verify($password, $row['password'])) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}
?>
