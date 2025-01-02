<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: logintecnico.php");
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

// Obtener el id del técnico logueado
$usuario = $_SESSION['usuario'];
$query = "SELECT id_tecnico FROM tecnicos WHERE usuario = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result = $stmt->get_result();
$fila = $result->fetch_assoc();
$id_tecnico = $fila['id_tecnico'];

$mensaje_modal = ""; // Variable para guardar el mensaje del modal

// Verificar si se ha subido una foto
if (isset($_FILES['foto_tecnico'])) {
    $foto = $_FILES['foto_tecnico'];
    
    // Verificar que la imagen sea válida
    if ($foto['error'] == 0) {
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $nombre_imagen = "foto_" . $id_tecnico . "." . $ext;  // Crear un nombre único para la imagen
        $ruta_imagen = "imagenes/tecnicos/" . $nombre_imagen;

        // Mover la imagen al directorio de imágenes
        if (move_uploaded_file($foto['tmp_name'], $ruta_imagen)) {
            // Actualizar la ruta de la foto en la base de datos
            $query_actualizar = "UPDATE tecnicos SET foto_tecnico = ? WHERE id_tecnico = ?";
            $stmt_actualizar = $conexion->prepare($query_actualizar);
            $stmt_actualizar->bind_param('si', $ruta_imagen, $id_tecnico);
            if ($stmt_actualizar->execute()) {
                $mensaje_modal = "La foto se ha actualizado correctamente.";
            } else {
                $mensaje_modal = "Error al actualizar la foto en la base de datos.";
            }
        } else {
            $mensaje_modal = "Error al subir la foto.";
        }
    } else {
        $mensaje_modal = "Error en el archivo subido.";
    }
} else {
    $mensaje_modal = "No se ha subido ninguna foto.";
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Foto</title>
    <style>
        body {
            background-color: #0A2E56; 
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); 
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
        }

        .modal-content h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="modal">
        <div class="modal-content">
            <h2><?php echo htmlspecialchars($mensaje_modal); ?></h2>
            <button onclick="window.location.href='dashboard_tecnicos.php';">Aceptar</button>
        </div>
    </div>
</body>
</html>
