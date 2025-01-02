<?php
// Conexión a la base de datos
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Iniciar sesión para acceder a los valores almacenados durante el login, como id_cliente y usuario.
session_start();

// Verificar si el cliente está logueado
if (!isset($_SESSION['usuario'])) {
    die("Error: No hay cliente autenticado.");
}

$id_cliente = $_SESSION['id_cliente'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $laboratorio = $_POST['laboratorio'];
    $unidad = $_POST['unidad'];
    $tipo_problema = $_POST['tipo_problema'];
    $descripcion_problema = $_POST['descripcion_problema'];

    $stmt = $conexion->prepare("INSERT INTO tickets (id_cliente, laboratorio, numero_unidad, tipo_problema, descripcion_problema) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiss", $id_cliente, $laboratorio, $unidad, $tipo_problema, $descripcion_problema);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Ticket enviado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al enviar el ticket: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();

    header("Location: dashboard_cliente.php");
    exit;
}
?>