<?php 
session_start();

//Si el usuario no esta logeado lo manda de nuevo a la pagina de login tecnico.
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

$message = ""; // Variable para mensajes del modal

// Caso: Marcar como Atendido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcar_atendido']) && isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];

    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
        $target_dir = "imagenes/reparaciones/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['imagenes']['name'][$key]);
            $target_file = $target_dir . $file_name;
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (in_array($_FILES['imagenes']['type'][$key], $allowed_types)) {
                if (move_uploaded_file($tmp_name, $target_file)) {
                    $query_insert_imagen = "INSERT INTO imagenes (id_ticket, ruta_imagen) VALUES (?, ?)";
                    $stmt_insert = $conexion->prepare($query_insert_imagen);
                    $stmt_insert->bind_param('is', $id_ticket, $target_file);
                    $stmt_insert->execute();
                }
            }
        }
    }

    $query_ticket = "UPDATE tickets SET Estado = 'Atendido' WHERE id_ticket = ?";
    $stmt_ticket = $conexion->prepare($query_ticket);
    $stmt_ticket->bind_param('i', $id_ticket);
    $stmt_ticket->execute();

    $query_tecnico = "UPDATE tecnicos SET Estado = 'Libre' WHERE id_tecnico = (SELECT id_tecnico_asignado FROM tickets WHERE id_ticket = ?)";
    $stmt_tecnico = $conexion->prepare($query_tecnico);
    $stmt_tecnico->bind_param('i', $id_ticket);
    $stmt_tecnico->execute();

    $message = "Ticket marcado como Atendido.";
}

// Caso: Marcar en Pausa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marcar_pausa'])) {
    $tickets_en_pausa = $_POST['tickets_en_pausa'] ?? [];
    if (!empty($tickets_en_pausa)) {
        foreach ($tickets_en_pausa as $id_ticket) {
            $query_actualizar = "UPDATE tickets SET Estado = 'En pausa' WHERE id_ticket = ?";
            $stmt_pausa = $conexion->prepare($query_actualizar);
            $stmt_pausa->bind_param('i', $id_ticket);
            $stmt_pausa->execute();
        }
        $message = "Tickets marcados como En Pausa exitosamente.";
    } else {
        $message = "No seleccionaste ningún ticket.";
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <style>
        
        .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4); 
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content button {
            background-color: #4CAF50; 
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
        }

        .modal-content button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<!-- Modal -->
<div id="resultModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('resultModal').style.display='none'">&times;</span>
        <p><?php echo $message; ?></p>
        <button onclick="document.getElementById('resultModal').style.display='none'">Aceptar</button>
    </div>
</div>

<script>
    // Mostrar el modal automáticamente si hay un mensaje
    document.addEventListener('DOMContentLoaded', function () {
        var message = "<?php echo $message; ?>";
        if (message) {
            document.getElementById('resultModal').style.display = 'block';
        }
    });
</script>

</body>
</html>
