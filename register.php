<?php
header('Content-Type: application/json');
include "config.php";

$name     = $_POST['name'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($name) || empty($email) || empty($phone) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

// Check if user already exists
$check = pg_query_params(
    $conn,
    "SELECT id FROM users WHERE email = $1 OR phone = $2",
    array($email, $phone)
);

if (pg_num_rows($check) > 0) {
    echo json_encode(["status" => "error", "message" => "User already exists"]);
    exit;
}

// Insert new user
$insert = pg_query_params(
    $conn,
    "INSERT INTO users (name, email, phone, password) VALUES ($1, $2, $3, $4)",
    array($name, $email, $phone, $hashed)
);

if ($insert) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Registration failed"]);
}
?>
