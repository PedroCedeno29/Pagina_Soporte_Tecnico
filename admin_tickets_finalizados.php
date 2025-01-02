<?php
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: loginadmin.php"); // Si no está logueado, redirige a login
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

// Consultar todos los tickets finalizados (Estado = 'Atendido') y obtener el nombre del técnico
$query_tickets_finalizados = "SELECT t.id_ticket, c.Nombres AS cliente_nombres, c.Apellidos AS cliente_apellidos, 
                              t.tipo_problema, t.descripcion_problema, t.Estado, t.comentario, t.laboratorio,
                              te.Nombres AS tecnico_nombres, te.Apellidos AS tecnico_apellidos
                              FROM tickets t
                              INNER JOIN clientes c ON t.id_cliente = c.id_cliente
                              INNER JOIN tecnicos te ON t.id_tecnico_asignado = te.id_tecnico
                              WHERE t.Estado = 'Atendido'"; // Solo los tickets 'Atendidos'
$result_tickets_finalizados = $conexion->query($query_tickets_finalizados);

if (!$result_tickets_finalizados) {
    die("Error en la consulta de tickets finalizados: " . $conexion->error);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets Finalizados - Administrador</title>
    <link rel="stylesheet" href="estilos/pagina_admin.css">  
</head>
<body>
    <div class="sidebar">
        <div class="admin-photo">
            <img src="imagenes/adminpedro.jpeg" alt="Foto del Administrador" />
        </div>
        <div class="admin-info">
            <h2>Bienvenido</h2>
            <h3>Administrador</h3>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard_admin.php">Página principal</a></li>
                <li><a href="admin_tickets_enproceso.php">Tickets en proceso</a></li>
                <li><a href="admin_tickets_finalizados.php">Tickets finalizados</a></li> <!-- Enlace a la página de tickets finalizados -->
            </ul>
        </nav>
        <a href="cerrar_sesion.php" class="logout-btn">Cerrar sesión</a>
    </div>

    <div class="main-content">
        <div class="table-container">
            <h1>Tickets Finalizados</h1>

            <?php if ($result_tickets_finalizados->num_rows == 0) { ?>
                <p>No hay tickets finalizados en este momento.</p>
            <?php } else { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Tipo de Problema</th>
                            <th>Laboratorio</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Técnico</th> <!-- Columna para el nombre del técnico -->
                            <th>Comentarios</th>
                            <th>Evidencia</th>  <!-- Columna para las imágenes -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Mostrar los tickets finalizados
                    while ($row_ticket = $result_tickets_finalizados->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row_ticket['cliente_nombres']) . " " . htmlspecialchars($row_ticket['cliente_apellidos']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['tipo_problema']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['laboratorio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['descripcion_problema']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['Estado']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['tecnico_nombres']) . " " . htmlspecialchars($row_ticket['tecnico_apellidos']) . "</td>"; // Mostrar el nombre del técnico
                        echo "<td>" . htmlspecialchars($row_ticket['comentario']) . "</td>";

                        // Consultar las imágenes asociadas al ticket
                        $query_imagenes = "SELECT ruta_imagen FROM imagenes WHERE id_ticket = ?";
                        $stmt_imagenes = $conexion->prepare($query_imagenes);
                        $stmt_imagenes->bind_param('i', $row_ticket['id_ticket']);
                        $stmt_imagenes->execute();
                        $result_imagenes = $stmt_imagenes->get_result();
                        echo "<td>";
                        if ($result_imagenes->num_rows > 0) {
                            while ($img_row = $result_imagenes->fetch_assoc()) {
                                echo "<img src='" . $img_row['ruta_imagen'] . "' width='100' height='100' style='margin: 5px;'>";
                            }
                        } else {
                            echo "No hay imágenes";
                        }
                        echo "</td>";

                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>

</body>
</html>

<?php
// Cerrar la conexión
$conexion->close();
?>
