<?php
header('Content-Type: application/json');
include "config.php";

$name     = $_POST['name'] ?? '';
$email    = $_POST['email'] ?? '';
$phone    = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

if(empty($name) || empty($email) || empty($phone) || empty($password)){
    echo json_encode(["status"=>"error","message"=>"Missing fields"]);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email=? OR phone=?");
mysqli_stmt_bind_param($stmt, "ss", $email, $phone);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) > 0){
    echo json_encode(["status"=>"error","message"=>"User already exists"]);
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO users (name,email,phone,password) VALUES (?,?,?,?)");
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $hashed);

if(mysqli_stmt_execute($stmt)){
    echo json_encode(["status"=>"success"]);
}else{
    echo json_encode(["status"=>"error","message"=>"Registration failed"]);
}
?>
