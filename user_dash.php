<?php
// File: C:\xampp\htdocs\vastu_website\login_submit.php

session_start(); // 🔥 SESSION START KARO

$host = "localhost";
$user = "root";
$password = "";
$db = "vastu_users";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password_input = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password_input, $user['password'])) {
        // ✅ Login successful – set session and redirect
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        header("Location: appointment_form.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
