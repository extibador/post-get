<?php
include('config.php');

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        handleGet($conn);
        break;
    case 'POST':
        handlePost($conn);
        break;
    case 'PUT':
        handlePut($conn);
        break;
    case 'DELETE':
        handleDelete($conn);
        break;
    default:
        echo json_encode(['message' => 'Método no soportado']);
        break;
}

function handleGet($conn) {
    $sql = "SELECT id, username, email FROM users";
    $result = $conn->query($sql);
    $users = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    echo json_encode($users);
}

function handlePost($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $query->bind_param("sss", $username, $email, $password);

    if ($query->execute()) {
        echo json_encode(['message' => 'Usuario creado']);
    } else {
        echo json_encode(['message' => 'Error: ' . $query->error]);
    }
}

function handlePut($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    $query = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
    $query->bind_param("sssi", $username, $email, $password, $id);

    if ($query->execute()) {
        echo json_encode(['message' => 'Usuario actualizado']);
    } else {
        echo json_encode(['message' => 'Error: ' . $query->error]);
    }
}

function handleDelete($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $query = $conn->prepare("DELETE FROM users WHERE id = ?");
    $query->bind_param("i", $id);

    if ($query->execute()) {
        echo json_encode(['message' => 'Usuario eliminado']);
    } else {
        echo json_encode(['message' => 'Error: ' . $query->error]);
    }
}
?>
