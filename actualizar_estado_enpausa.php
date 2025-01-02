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

$message = ""; // Variable para almacenar el mensaje del modal.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcar_pausa'])) {
    $tickets_en_pausa = $_POST['tickets_en_pausa'] ?? []; // Aquí tickets_en_pausa hacen referencia a los checkbox. Si no se seleccionaron, se asigna un array vacío.
    if (!empty($tickets_en_pausa)) {
        foreach ($tickets_en_pausa as $id_ticket) { // Itera sobre los id.
            $query_actualizar = "UPDATE tickets SET Estado = 'En pausa' WHERE id_ticket = ?";
            $stmt = $conexion->prepare($query_actualizar); // Asigna el id del ticket a la consulta.
            $stmt->bind_param('i', $id_ticket);
            $stmt->execute();
        }
        $message = "Tickets marcados como 'En Pausa' exitosamente.";
    } else {
        $message = "No seleccionaste ningún ticket.";
    }
} else {
    $message = "Solicitud inválida.";
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            border-bottom: none;
        }

        .modal-title {
            font-weight: bold;
        }

        .modal-body {
            font-size: 16px;
            color: #555;
        }

        .modal-footer {
            border-top: none;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Resultado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <?php echo $message; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.location.href='dashboard_tecnicos.php'">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
        resultModal.show();
    });
</script>

</body>
</html>
