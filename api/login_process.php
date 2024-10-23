<?php
session_start();

$validUsername = 'Leon';
$validPassword = 'Leon99';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['username'] = $username;
        header("Location: home.html");
        exit();
    } else {
        header("Location: login.html?username_error=Username atau password salah");
        exit();
    }
}
?>
