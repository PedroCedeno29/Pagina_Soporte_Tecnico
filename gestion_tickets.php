<?php
// Iniciar sesión para guardar el mensaje
session_start();

// Conexión a la base de datos
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if (isset($_POST['asignar'])) {
    // Verificar si se seleccionaron tickets
    if (isset($_POST['tickets']) && is_array($_POST['tickets']) && count($_POST['tickets']) > 0) {
        // Iterar sobre todos los tickets seleccionados
        foreach ($_POST['tickets'] as $ticket_id) {
            // Obtener el ID del técnico asignado para este ticket
            $tecnico_id = $_POST['tecnico_' . $ticket_id];

            // Actualizar el ticket asignando el técnico y cambiando su estado a 'En proceso'
            $query_asignar_ticket = "UPDATE tickets SET id_tecnico_asignado = ?, Estado = 'En proceso' WHERE id_ticket = ?";
            $stmt = $conexion->prepare($query_asignar_ticket);
            $stmt->bind_param('ii', $tecnico_id, $ticket_id);
            if (!$stmt->execute()) {
                die("Error al asignar técnico al ticket: " . $stmt->error);
            }

            // Actualizar el estado del técnico a 'Ocupado'
            $query_actualizar_tecnico = "UPDATE tecnicos SET Estado = 'Ocupado' WHERE id_tecnico = ?";
            $stmt_tecnico = $conexion->prepare($query_actualizar_tecnico);
            $stmt_tecnico->bind_param('i', $tecnico_id);
            if (!$stmt_tecnico->execute()) {
                die("Error al actualizar estado del técnico: " . $stmt_tecnico->error);
            }
        }

        // Mensaje de éxito
        $_SESSION['mensaje'] = "Técnico asignado correctamente.";
    } else {
        // Si no se seleccionaron tickets
        $_SESSION['mensaje'] = "No ha seleccionado ningún técnico.";
    }

    // Redirigir a la página de administración
    header("Location: dashboard_admin.php");
    exit;
}
?>