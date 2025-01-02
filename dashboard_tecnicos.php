<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: logintecnico.php"); // Si no está logueado, redirige a login
    exit();
}

$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el usuario que inició sesión
$usuario = $_SESSION['usuario'];

// Consultar el ID y los nombres del técnico a partir del nombre de usuario
$query_tecnico_id = "SELECT id_tecnico, Nombres, Apellidos, foto_tecnico FROM tecnicos WHERE usuario = ?";
$stmt = $conexion->prepare($query_tecnico_id);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result_tecnico = $stmt->get_result();

if ($result_tecnico->num_rows == 1) {
    $fila_tecnico = $result_tecnico->fetch_assoc();
    $id_tecnico = $fila_tecnico['id_tecnico'];
    $nombre_tecnico = $fila_tecnico['Nombres'] . ' ' . $fila_tecnico['Apellidos'];  // Nombre completo
    $foto_tecnico = $fila_tecnico['foto_tecnico'] ? $fila_tecnico['foto_tecnico'] : 'imagenes/default.jpg'; // Ruta por defecto si no tiene foto
} else {
    die("Usuario no encontrado.");
}

// Consultar los tickets asignados al técnico (excluyendo los 'Atendidos')
$query_tickets = "SELECT t.id_ticket, c.Nombres, c.Apellidos, t.tipo_problema, t.descripcion_problema, t.fecha_registro_ticket, t.Estado, t.laboratorio
                FROM tickets t
                INNER JOIN clientes c ON t.id_cliente = c.id_cliente
                WHERE t.id_tecnico_asignado = ? AND t.Estado != 'Atendido'"; // Excluye los tickets 'Atendidos'
$stmt = $conexion->prepare($query_tickets);
$stmt->bind_param('i', $id_tecnico);
$stmt->execute();
$result_tickets = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tickets - Técnico</title>
    <link rel="stylesheet" href="estilos/pagina_tecnico.css">
</head>
<body>
<div class="sidebar">
    <div class="admin-photo">
        <img src="<?php echo $foto_tecnico; ?>" alt="Foto del Técnico">
    </div>
    <div class="admin-info">
        <h2>Bienvenido</h2>
        <h3><?php echo $nombre_tecnico; ?></h3> <!-- Nombre completo del técnico -->
    </div>
    <nav>
        <ul>
            <li><a href="dashboard_tecnicos.php">Tickets pendientes</a></li>
            <li><a href="tecnico_tickets_finalizados.php">Tickets finalizados</a></li>
        </ul>
    </nav>
    <a href="cerrar_sesion.php" class="logout-btn">Cerrar sesión</a>
</div>

<div class="main-content">
    <div class="table-container">
        <h1>Tickets Asignados</h1>

        <?php if ($result_tickets->num_rows == 0) { ?>
            <p>No tienes tickets asignados en este momento.</p>
        <?php } else { ?>
            <form method="POST" action="actualizar_estado_ticket.php" enctype="multipart/form-data"> <!--multipart/form-data: Es una codificación especial necesaria para enviar archivos binarios (como imágenes, videos, documentos, etc.) junto con otros datos del formulario.-->
                                                                                                    <!--Si no se incluye el multipart los datos del archivo no estarán disponibles en la superglobal $_FILES de PHP-->
                <table>
                    <thead>
                        <tr>
                            <th>Seleccionar</th> 
                            <th>Cliente</th>
                            <th>Tipo de Problema</th>
                            <th>Laboratorio</th>
                            <th>Descripción</th>
                            <th>Fecha de registro</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    while ($row_ticket = $result_tickets->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='tickets_en_pausa[]' value='" . $row_ticket['id_ticket'] . "'></td>"; // Checkbox con el ID del ticket
                        echo "<td>" . htmlspecialchars($row_ticket['Nombres']) . " " . htmlspecialchars($row_ticket['Apellidos']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['tipo_problema']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['laboratorio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['descripcion_problema']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['fecha_registro_ticket']) . "</td>";
                        echo "<td>";
                        ?>
                        <!-- Botón para marcar como atendido -->
                        <input type="hidden" name="id_ticket" value="<?php echo $row_ticket['id_ticket']; ?>">
                        <label for="imagenes_<?php echo $row_ticket['id_ticket']; ?>" class="button-subir-imagen">Subir imágenes</label>
                        <br><br>
                        <input type="file" name="imagenes[]" id="imagenes_<?php echo $row_ticket['id_ticket']; ?>" accept="image/*,.webp" multiple> <!--El campo imagenes[] permitirá seleccionar múltiples archivos, y los datos se enviarán a través de $_FILES['imagenes'].-->
                        <button type="submit" name="marcar_atendido" value="marcar_atendido" class="button-atendido">Marcar como Atendido</button>
                        <?php
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <br>
                <button type="submit" name="marcar_pausa" value="marcar_pausa" class="button-pausa">Marcar en Pausa</button>
            </form>
        <?php } ?>
    </div>
</div>
</body>
</html>

<?php
$conexion->close();
?>