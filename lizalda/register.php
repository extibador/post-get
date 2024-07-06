<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $query->bind_param("sss", $username, $email, $password);

    if ($query->execute()) {
        header('Location: index.html');
        exit;
    } else {
        echo "Error: " . $query->error;
    }
}
?>
