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
        $stmt = $conexion->prepare("SELECT id_tecnico, Usuario, Contraseña, Estado, Nombres, Apellidos, foto_tecnico FROM tecnicos WHERE Usuario = ? AND (Estado = 'Libre' OR Estado = 'Ocupado')");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verificar si se encontró el usuario
        if ($resultado->num_rows == 1) {
            $fila = $resultado->fetch_assoc();
            $hash_almacenado = $fila['Contraseña'];
        
            if ($contraseña === $hash_almacenado) { // Comparar contraseña de texto sin formato
                // Iniciar sesión y establecer variables de sesión
                session_start();
                $_SESSION['usuario'] = $fila['Usuario'];
                $_SESSION['id_tecnico'] = $fila['id_tecnico'];
                $_SESSION['nombre_tecnico'] = $fila['Nombres'] . ' ' . $fila['Apellidos']; // Concatenar nombre y apellido
                $_SESSION['foto_tecnico'] = $fila['foto_tecnico']; // Asignar la ruta de la foto
                header("Location: dashboard_tecnicos.php");
                exit();
            } else {
                $error_message = "Contraseña incorrecta.";
            }
        }
        
        
        else {
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
        /* Estilo para el modal */
        .modal {
            display: none; /* Inicialmente oculto */
            position: fixed;
            z-index: 1; /* Se muestra encima de todo */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Fondo semitransparente */
            padding-top: 50px; /* Ajustar para centrar mejor */
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
        <h1>Inicie su sesión como técnico</h1>
        <form action="logintecnico.php" method="POST">
            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required><br><br>

            <label for="contraseña">Contraseña:</label><br>
            <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" required><br><br>

            <input type="submit" value="Iniciar Sesión">
        </form>
        <hr class="separator">
        <a href="paginalogins.html" class="link-volver">Regresar</a>
    </div>

    <!-- Modal de error -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?= $error_message; ?></p>
        </div>
    </div>

    <script>
        // Mostrar el modal si hay un mensaje de error
        <?php if (!empty($error_message)): ?>
            var modal = document.getElementById("errorModal");
            var span = document.getElementsByClassName("close")[0];

            // Mostrar el modal
            modal.style.display = "block";

            // Cuando el usuario hace clic en <span> (x), cerrar el modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // Cuando el usuario hace clic en cualquier parte fuera del modal, también lo cierra
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        <?php endif; ?>
    </script>

</body>
</html>
