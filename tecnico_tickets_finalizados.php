<?php
session_start();

// Verificar si el técnico está logueado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_tecnico'])) {
    header("Location: logintecnico.php"); // Si no está logueado, redirige al login
    exit();
}

// Conexión a la base de datos
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consultar todos los tickets finalizados (Estado = 'Atendido') para el técnico logueado
$query_tickets_finalizados = "SELECT t.id_ticket, c.Nombres AS cliente_nombres, c.Apellidos AS cliente_apellidos, 
                              t.tipo_problema, t.descripcion_problema, t.fecha_registro_ticket, t.Estado, t.comentario, t.laboratorio,
                              te.Nombres AS tecnico_nombres, te.Apellidos AS tecnico_apellidos
                              FROM tickets t
                              INNER JOIN clientes c ON t.id_cliente = c.id_cliente
                              INNER JOIN tecnicos te ON t.id_tecnico_asignado = te.id_tecnico
                              WHERE t.Estado = 'Atendido' AND t.id_tecnico_asignado = ?";
$stmt_tickets = $conexion->prepare($query_tickets_finalizados);
$stmt_tickets->bind_param('i', $_SESSION['id_tecnico']);
$stmt_tickets->execute();
$result_tickets_finalizados = $stmt_tickets->get_result();

if (!$result_tickets_finalizados) {
    die("Error en la consulta de tickets finalizados: " . $conexion->error);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets Finalizados - Técnico</title>
    <link rel="stylesheet" href="estilos/pagina_tecnico.css">  
</head>
<body>
    <div class="sidebar">
        <div class="admin-photo">
            <!-- Mostrar la foto del técnico desde la sesión -->
            <?php
            $foto_tecnico = htmlspecialchars($_SESSION['foto_tecnico']);
            if (file_exists($foto_tecnico)) {
                echo "<img src='{$foto_tecnico}' alt='Foto del Técnico'>";
            } else {
                echo "<img src='imagenes/default_tecnico.png' alt='Foto por defecto'>";
            }
            ?>
        </div>
        <div class="admin-info">
            <h2>Bienvenido</h2>
            <!-- Mostrar el nombre y apellido del técnico desde la sesión -->
            <h3><?php echo htmlspecialchars($_SESSION['nombre_tecnico']); ?></h3>
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
                            <th>Técnico</th>
                            <th>Comentarios</th>
                            <th>Evidencia</th>
                            <th>Fecha de registro</th>
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
                        echo "<td>" . htmlspecialchars($row_ticket['tecnico_nombres']) . " " . htmlspecialchars($row_ticket['tecnico_apellidos']) . "</td>";
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
                                echo "<img src='" . htmlspecialchars($img_row['ruta_imagen']) . "' width='100' height='100' style='margin: 5px;'>";
                            }
                        } else {
                            echo "No hay imágenes";
                        }
                        echo "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['fecha_registro_ticket']) . "</td>";

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
