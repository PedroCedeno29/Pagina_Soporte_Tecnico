<?php
session_start();

// Verificar si el cliente está logueado
if (!isset($_SESSION['usuario'])) {
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

// Verificar si se recibió el ID del ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_ticket'])) {
    $id_ticket = $_POST['id_ticket'];

    // Procesar el comentario enviado
    if (isset($_POST['comentario']) && !empty(trim($_POST['comentario']))) {
        $comentario = trim($_POST['comentario']);
        $query_actualizar = "UPDATE tickets SET comentario = ? WHERE id_ticket = ?";
        $stmt = $conexion->prepare($query_actualizar);
        $stmt->bind_param('si', $comentario, $id_ticket); //hay 2 tipos de datos en el parametro, string e integer.

        if ($stmt->execute()) { //Mostrat un mensaje que se ha enviado el commentario correctamente.
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('confirmationModal').style.display = 'flex';
                });
            </script>";
        } else {
            $_SESSION['mensaje'] = "Error al enviar el comentario. Inténtalo nuevamente.";
            header("Location: ver_tickets.php");
            exit();
        }
    }
} else {
    die("No se proporcionó un ID de ticket.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Comentario</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #1a1a2e;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }

        .modal-content {
            background: #16213e;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .modal-content h2 {
            margin-bottom: 20px;
            font-size: 20px;
        }

        .modal-content textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .modal-content textarea:focus {
            outline: none;
            box-shadow: 0 0 4px #0078d4;
        }

        .modal-content button {
            padding: 10px 20px;
            background-color: #0078d4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .modal-content button:hover {
            background-color: #005fa3;
        }

        .modal-content .close-btn {
            background-color: #e74c3c;
            margin-left: 10px;
        }

        .modal-content .close-btn:hover {
            background-color: #c0392b;
        }

        #confirmationModal {
            display: none;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 2000;
        }

        #confirmationModal .modal-content {
            background: #16213e;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        #confirmationModal .modal-content button {
            background-color: #0078d4;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        #confirmationModal .modal-content button:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
    <div class="modal">
        <div class="modal-content">
            <h2>Escribe tu comentario</h2>
            <form method="POST" action="">
                <textarea name="comentario" placeholder="Escribe tu comentario sobre la atención..." required></textarea>
                <input type="hidden" name="id_ticket" value="<?php echo htmlspecialchars($id_ticket); ?>">
                <button type="submit">Enviar</button>
                <button type="button" class="close-btn" onclick="window.location.href='ver_tickets.php'">Cancelar</button>
            </form>
        </div>
    </div>

    <div id="confirmationModal">
        <div class="modal-content">
            <h2>Comentario enviado correctamente</h2>
            <button onclick="window.location.href='ver_tickets.php'">Aceptar</button>
        </div>
    </div>
</body>
</html>
