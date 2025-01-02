<?php
// Conexión a la base de datos
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

// Verificar si la conexión fue exitosa
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener el ID del ticket desde la URL
if (isset($_GET['id_ticket'])) {
    $id_ticket = $_GET['id_ticket'];

    // Consultar la base de datos para obtener la ruta de la imagen asociada al ticket
    $query_imagen = "SELECT ruta_imagen FROM imagenes WHERE id_ticket = ?";
    $stmt = $conexion->prepare($query_imagen);
    $stmt->bind_param('i', $id_ticket); // Vincular el ID del ticket
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si se encontró la imagen
    if ($resultado->num_rows > 0) {
        $row_imagen = $resultado->fetch_assoc();
        $ruta_imagen = $row_imagen['ruta_imagen']; // Usar la ruta relativa directamente

        // Validar si el archivo realmente existe
        if (!file_exists($ruta_imagen)) {
            $error_message = "La imagen no está disponible o no existe en el servidor.";
        }
    } else {
        $error_message = "No se ha encontrado una imagen para este ticket.";
    }
} else {
    $error_message = "No se ha proporcionado un ID de ticket.";
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Imagen</title>
    <link rel="stylesheet" href="estilos/estilos.css">
    <style>
        body {
            background-color: #0d47a1; 
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 600px;
        }

        h1 {
            color: #333;
        }

        img {
            max-width: 100%;
            height: auto;
            max-height: 70vh; 
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn-volver:hover {
            background-color: #0056b3;
        }

        p {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Evidencias</h1>

        <?php if (isset($ruta_imagen) && !isset($error_message)): ?>
            <img src="<?php echo htmlspecialchars($ruta_imagen); ?>" alt="Imagen del Ticket">
        <?php else: ?>
            <p><?php echo isset($error_message) ? htmlspecialchars($error_message) : "Ha ocurrido un error inesperado."; ?></p>
        <?php endif; ?>

        <br>
        <a href="ver_tickets.php" class="btn-volver">Volver a los Tickets</a>
    </div>
</body>
</html>
