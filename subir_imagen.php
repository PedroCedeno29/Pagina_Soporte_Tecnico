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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen'])) {
    $id_ticket = $_POST['id_ticket'];
    $imagen = $_FILES['imagen'];

    // Validar si es una imagen
    $ext = pathinfo($imagen['name'], PATHINFO_EXTENSION);
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array(strtolower($ext), $permitidas)) {
        die("Solo se permiten imágenes (JPG, JPEG, PNG, GIF, WEBP).");
    }

    // Crear un nombre único para la imagen
    $nombre_imagen = uniqid() . '.' . $ext;
    $ruta_directorio = 'imagenes/reparaciones/';
    $ruta_destino = $ruta_directorio . $nombre_imagen;

    // Verificar si el directorio de destino existe
    if (!is_dir($ruta_directorio)) {
        mkdir($ruta_directorio, 0777, true);
    }

    // Mover la imagen al directorio destino
    if (move_uploaded_file($imagen['tmp_name'], $ruta_destino)) {
        // Insertar en la base de datos la ruta relativa de la imagen
        $ruta_relativa = 'reparaciones/' . $nombre_imagen;
        $query = "INSERT INTO imagenes (id_ticket, ruta_imagen) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('is', $id_ticket, $ruta_relativa);
        if ($stmt->execute()) {
            echo "Imagen subida correctamente.";
            header("Location: dashboard_tecnicos.php?mensaje=imagen_subida");
            exit();
        } else {
            echo "Error al guardar la imagen en la base de datos.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
}

$conexion->close();
?>