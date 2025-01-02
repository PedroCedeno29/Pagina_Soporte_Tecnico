<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$error_message = "";  // Variable para guardar los mensajes de error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $contraseña = trim($_POST['contraseña']);

    if (empty($usuario) || empty($contraseña)) {
        $error_message = "Por favor, complete todos los campos.";
    } else {
        $stmt = $conexion->prepare("SELECT id_cliente, Usuario, Contraseña, Estado FROM clientes WHERE usuario = ? AND Estado = 'A'");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar si se encontró el usuario
        if ($resultado->num_rows == 1) {
            $fila = $resultado->fetch_assoc();
            $hash_almacenado = $fila['Contraseña'];

            if ($contraseña === $hash_almacenado) { // Comparar contraseña de texto sin formato
                // Iniciar sesión y redirigir
                session_start();
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id_cliente'] = $fila['id_cliente']; // Guardar ID del cliente
                header("Location: dashboard_cliente.php");
                exit();
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        } else {
            $error_message = "Usuario no encontrado o inactivo.";
        }

        // Cerrar conexión
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilos/estilos.css">
    <style>
        .modal {
            display: none; 
            position: fixed;
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); 
            padding-top: 50px; 
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-content p {
            color: red;
            font-size: 16px;
            margin: 0;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 15px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Inicie su sesión como cliente</h1>
        <form action="logincliente.php" method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>

            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" required>

            <input type="submit" value="Iniciar Sesión">
        </form>
        <hr class="separator">
        <a href="paginalogins.html" class="link-volver">Regresar</a>
        <a href="nuevacuenta.php" class="link-volver">Crear una cuenta</a>
    </div>

    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?= $error_message; ?></p>
        </div>
    </div>

    <script>
        <?php if (!empty($error_message)): ?>
            var modal = document.getElementById("errorModal");
            var span = document.getElementsByClassName("close")[0];

            modal.style.display = "block";

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        <?php endif; ?>
    </script>

</body>
</html>
