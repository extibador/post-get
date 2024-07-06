<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="welcome-container">
        <h1>Bienvenido, <?php echo htmlspecialchars($username); ?>!</h1>
        <a href="logout.php">Cerrar Sesión</a>
        <a href="users.php">Gestionar Usuarios</a>
        <a href="tasks.php">Tareas</a>
    </div>
</body>
</html>
