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
    $sql = "SELECT id, name, description, date_assigned FROM tasks";
    $result = $conn->query($sql);
    $tasks = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }

    echo json_encode($tasks);
}

function handlePost($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $description = $data['description'];
    $dateAssigned = $data['date_assigned'];

    $query = $conn->prepare("INSERT INTO tasks (name, description, date_assigned) VALUES (?, ?, ?)");
    $query->bind_param("sss", $name, $description, $dateAssigned);

    if ($query->execute()) {
        echo json_encode(['message' => 'Tarea creada']);
    } else {
        echo json_encode(['message' => 'Error: ' . $query->error]);
    }
}

function handlePut($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $name = $data['name'];
    $description = $data['description'];
    $dateAssigned = $data['date_assigned'];

    $query = $conn->prepare("UPDATE tasks SET name = ?, description = ?, date_assigned = ? WHERE id = ?");
    $query->bind_param("sssi", $name, $description, $dateAssigned, $id);

    if ($query->execute()) {
        echo json_encode(['message' => 'Tarea actualizada']);
    } else {
        echo json_encode(['message' => 'Error: ' . $query->error]);
    }
}

function handleDelete($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $query = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $query->bind_param("i", $id);

    if ($query->execute()) {
        echo json_encode(['message' => 'Tarea eliminada']);
    } else {
        echo json_encode(['message' => 'Error: ' . $query->error]);
    }
}
?>
