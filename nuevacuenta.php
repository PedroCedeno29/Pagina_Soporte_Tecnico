<?php
// Conexión a la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "solutions";

$conexion = new mysqli($server, $user, $pass, $db);

// Verificar conexión
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$cuentaCreada = false; // Variable para mostrar el modal

// Comprobar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_cuenta'])) {
    // Recibir y limpiar los datos del formulario
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $usuario = trim($_POST['usuario']);
    $contraseña = trim($_POST['contraseña']);

    // Validar que no haya campos vacíos
    if (empty($nombres) || empty($apellidos) || empty($correo) || empty($telefono) || empty($usuario) || empty($contraseña)) {
        die("Todos los campos son obligatorios.");
    }

    // Inicializar la variable
    $user_almacenado = null;

    // Validar si el usuario ya existe
    $validacion = $conexion->prepare("SELECT Usuario FROM clientes WHERE usuario = ? AND Estado = 'A'");
    $validacion->bind_param('s', $usuario);
    $validacion->execute();
    $resultado = $validacion->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $user_almacenado = $fila['Usuario'];
    }

    // Comparar y verificar si el usuario ya existe
    if ($usuario == $user_almacenado) {
        die("El usuario ya existe. Por favor ingrese otro.");
    }

    // Consulta para insertar datos
    $insertardatos = "INSERT INTO clientes (nombres, apellidos, correo, telefono, usuario, contraseña) 
                      VALUES ('$nombres', '$apellidos', '$correo', '$telefono', '$usuario', '$contraseña')";

    // Ejecutar consulta
    if ($conexion->query($insertardatos) === TRUE) {
        $cuentaCreada = true; // Activar el modal de éxito
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Soluciones TecLab</title>
    <link rel="stylesheet" href="estilos/estilos.css">
    <style>
        body {
            background: #04243f;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        .login-container h1 {
            font-size: 30px;
            font-family: 'Impact', 'Arial Black', sans-serif;
            color: #0078d4;
            margin-bottom: 20px;
        }

        .login-container label {
            font-weight: bold;
            color: #555;
            text-align: left;
            display: block;
            margin-bottom: 8px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-container button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .login-container button:hover {
            background-color: #28a745;
            transform: scale(1.05);
        }

        .login-container a {
            display: inline-block;
            margin-top: 15px;
            color: #0078d4;
            text-decoration: none;
            font-weight: bold;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .login-container .footer-text {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        /* Estilo para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-content h2 {
            color: #28a745;
            margin-bottom: 15px;
        }

        .modal-content button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-content button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Crear tu cuenta</h1>
        <p>Por favor, completa la información para registrarte.</p>
        <form action="" method="POST">
            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" placeholder="Ingrese sus nombres" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese sus apellidos" required>

            <label for="correo">Correo:</label>
            <input type="text" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" placeholder="Ingrese su número de teléfono" required>

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingrese un nombre de usuario" required>

            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" required>

            <button type="submit" name="crear_cuenta" value="crear_cuenta">Crear Cuenta</button>
        </form>
        <a href="index.html">Volver a la página principal</a>
        <p class="footer-text">Al registrarte aceptas nuestros términos y condiciones.</p>
    </div>

    <?php if ($cuentaCreada): ?>
    <div class="modal" id="successModal">
        <div class="modal-content">
            <h2>Cuenta creada exitosamente</h2>
            <button onclick="closeModal()">Aceptar</button>
        </div>
    </div>
    <script>
        document.getElementById('successModal').style.display = 'flex';
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }
    </script>
    <?php endif; ?>
</body>
</html>
