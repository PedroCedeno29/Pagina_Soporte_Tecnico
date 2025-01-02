<?php
session_start();

// Verificar si el cliente está logueado
if (!isset($_SESSION['usuario'])) {
    // Si no está logueado, redirige a la página de login
    header("Location: logincliente.php");
    exit();
}

$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

// Verificar si la conexión fue exitosa
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el ID del cliente logueado
$usuario_cliente = $_SESSION['usuario']; // El nombre de usuario está en la sesión
$query_cliente_id = "SELECT id_cliente FROM clientes WHERE usuario = ?";
$stmt = $conexion->prepare($query_cliente_id);
$stmt->bind_param('s', $usuario_cliente);
$stmt->execute();
$result_cliente = $stmt->get_result();

if ($result_cliente->num_rows == 1) {
    $fila_cliente = $result_cliente->fetch_assoc();
    $id_cliente = $fila_cliente['id_cliente'];
} else {
    die("Usuario no encontrado.");
}

// Consultar los tickets solo del cliente logueado
$query_tickets = "SELECT t.id_ticket, t.tipo_problema, t.descripcion_problema, t.Estado, t.laboratorio, i.ruta_imagen
                FROM tickets t
                LEFT JOIN imagenes i ON t.id_ticket = i.id_ticket
                WHERE t.id_cliente = ?"; // Filtra los tickets por el ID del cliente logueado
$stmt = $conexion->prepare($query_tickets);
$stmt->bind_param('i', $id_cliente);
$stmt->execute();
$result_tickets = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tickets</title>
    <link rel="stylesheet" href="estilos/estilo_tickets_clientes.css">
</head>
<body>
    <div class="container">
        <h1>Mis Tickets</h1>

        <?php if ($result_tickets->num_rows == 0) { ?>
            <p>No tienes tickets registrados.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Tipo de Problema</th>
                        <th>Laboratorio</th>
                        <th>Descripción</th>
                        <th>Resultado</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row_ticket = $result_tickets->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row_ticket['tipo_problema']); ?></td>
                        <td><?php echo htmlspecialchars($row_ticket['laboratorio']); ?></td>
                        <td><?php echo htmlspecialchars($row_ticket['descripcion_problema']); ?></td>
                        <td>
                            <form method="GET" action="ver_imagenes.php">
                                <input type="hidden" name="id_ticket" value="<?php echo $row_ticket['id_ticket']; ?>">
                                <input type="submit" value="Ver Imágenes">
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="enviar_comentario.php">
                                <input type="hidden" name="id_ticket" value="<?php echo $row_ticket['id_ticket']; ?>">
                                <input type="submit" value="Enviar Comentario">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <br><br>

        <h3><a href="dashboard_cliente.php" class = "link-volver">Regresar</a></h3>
    </div>
</body>
</html>

<?php

$conexion->close();
?>
