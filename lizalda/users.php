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
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="users-container">
        <h2>Gestión de Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <!-- Usuarios se cargarán aquí -->
            </tbody>
        </table>
        <a href="welcome.php">Volver</a>
    </div>

    <script>
        function loadUsers() {
            $.ajax({
                url: 'api.php',
                method: 'GET',
                success: function(data) {
                    var usersTableBody = $('#usersTableBody');
                    usersTableBody.empty();

                    data.forEach(function(user) {
                        var row = `<tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td class="actions">
                                <button onclick="deleteUser(${user.id})">Eliminar</button>
                                <button onclick="editUser(${user.id}, '${user.username}', '${user.email}')">Editar</button>
                            </td>
                        </tr>`;
                        usersTableBody.append(row);
                    });
                }
            });
        }

        function deleteUser(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                $.ajax({
                    url: 'api.php',
                    method: 'DELETE',
                    data: JSON.stringify({ id: id }),
                    success: function(response) {
                        alert(response.message);
                        loadUsers();
                    }
                });
            }
        }

        function editUser(id, username, email) {
            var newUsername = prompt('Nuevo nombre de usuario:', username);
            var newEmail = prompt('Nuevo email:', email);
            var newPassword = prompt('Nueva contraseña:');

            if (newUsername && newEmail && newPassword) {
                $.ajax({
                    url: 'api.php',
                    method: 'PUT',
                    data: JSON.stringify({
                        id: id,
                        username: newUsername,
                        email: newEmail,
                        password: newPassword
                    }),
                    success: function(response) {
                        alert(response.message);
                        loadUsers();
                    }
                });
            }
        }

        $(document).ready(function() {
            loadUsers();
        });
    </script>
</body>
</html>
