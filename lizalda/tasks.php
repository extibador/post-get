<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="tasks-container">
        <h2>Gestión de Tareas</h2>
        <form id="taskForm">
            <label for="taskName">Nombre de Tarea</label>
            <input type="text" id="taskName" name="taskName" required>
            <label for="taskDescription">Descripción</label>
            <textarea id="taskDescription" name="taskDescription" required></textarea>
            <label for="taskDate">Fecha de Asignación</label>
            <input type="date" id="taskDate" name="taskDate" required>
            <button type="submit">Agregar Tarea</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Tarea</th>
                    <th>Descripción</th>
                    <th>Fecha de Asignación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tasksTableBody">
                <!-- Tareas se cargarán aquí -->
            </tbody>
        </table>
        <a href="welcome.php">Volver</a>
    </div>

    <script>
        function loadTasks() {
            $.ajax({
                url: 'tasks_api.php',
                method: 'GET',
                success: function(data) {
                    var tasksTableBody = $('#tasksTableBody');
                    tasksTableBody.empty();

                    data.forEach(function(task) {
                        var row = `<tr>
                            <td>${task.id}</td>
                            <td>${task.name}</td>
                            <td>${task.description}</td>
                            <td>${task.date_assigned}</td>
                            <td class="actions">
                                <button onclick="deleteTask(${task.id})">Eliminar</button>
                                <button onclick="editTask(${task.id}, '${task.name}', '${task.description}', '${task.date_assigned}')">Editar</button>
                            </td>
                        </tr>`;
                        tasksTableBody.append(row);
                    });
                }
            });
        }

        function deleteTask(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
                $.ajax({
                    url: 'tasks_api.php',
                    method: 'DELETE',
                    data: JSON.stringify({ id: id }),
                    success: function(response) {
                        alert(response.message);
                        loadTasks();
                    }
                });
            }
        }

        function editTask(id, name, description, dateAssigned) {
            var newName = prompt('Nuevo nombre de tarea:', name);
            var newDescription = prompt('Nueva descripción:', description);
            var newDateAssigned = prompt('Nueva fecha de asignación:', dateAssigned);

            if (newName && newDescription && newDateAssigned) {
                $.ajax({
                    url: 'tasks_api.php',
                    method: 'PUT',
                    data: JSON.stringify({
                        id: id,
                        name: newName,
                        description: newDescription,
                        date_assigned: newDateAssigned
                    }),
                    success: function(response) {
                        alert(response.message);
                        loadTasks();
                    }
                });
            }
        }

        $('#taskForm').on('submit', function(event) {
            event.preventDefault();

            var taskName = $('#taskName').val();
            var taskDescription = $('#taskDescription').val();
            var taskDate = $('#taskDate').val();

            $.ajax({
                url: 'tasks_api.php',
                method: 'POST',
                data: JSON.stringify({
                    name: taskName,
                    description: taskDescription,
                    date_assigned: taskDate
                }),
                success: function(response) {
                    alert(response.message);
                    loadTasks();
                }
            });
        });

        $(document).ready(function() {
            loadTasks();
        });
    </script>
</body>
</html>
