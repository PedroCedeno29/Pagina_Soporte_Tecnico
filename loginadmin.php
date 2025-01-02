<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

//Aquí se crea una instancia de la clase mysqli para realizar la conexión a la base de datos y permitir la interacción con la misma.
//El constructor  new mysqli crea un objeto para manejar la base de datos usando 4 parámetros.
$conexion = new mysqli($server, $user, $pass, $db); 

//Aqui se usa la propiedad pública de la clase mysqli connect_errno que sirve para verificar si ocurrio un error durante la conexión a la BBDD.
//Si hay un error su valor será 1 y si es 0 quiere decir que no hubo errores.
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error); //la funcion die() detiene automaticamente la ejecución del script y muestra el mensaje de error.
    //conexión_error contiene un mensaje más descriptivo del error. Ejemplo: No such file or directory.

}

// Variable para guardar mensajes de error
$error_message = "";  

//Comprobación de si el form se envío utilizando el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $contraseña = trim($_POST['contraseña']);

    if (empty($usuario) || empty($contraseña)) {
        $error_message = "Por favor, complete todos los campos.";
    } else {

        //El método prepare() sirve para crear una consulta. Es necesario para evitar inyecciones SQL.
        //prepare(): Define la consulta con marcadores de posición (?) en lugar de incluir directamente los datos.
        //Se utiliza para enlazar los valores reales a estos marcadores de posición.
        //En lugar de interpretar los datos del usuario como parte del código SQL, se tratan como literales (valores fijos).
        $stmt = $conexion->prepare("SELECT id_administrador, Usuario, Contraseña, Estado FROM administradores WHERE usuario = ? AND Estado = 'A'"); //El signo '?' es un marcador de posición que será reemplazado.
        $stmt->bind_param("s", $usuario); //bind_param() reemplaza el marcador  '?' con el valor de $usuario esto para prevenir ataques de inyecciones SQL.
        $stmt->execute(); //Ejecuta la consulta.
        $resultado = $stmt->get_result(); // get_result() obtiene la fila o filas resultantes de la consulta ejecutada. 

        // Verificar si se encontró el usuario
        if ($resultado->num_rows == 1) {
            //El método fetch_assoc() devuelve la fila como un array asociativo, donde las claves del arreglo son los nombres de las columnas de la tabla.
            //En un array asociativo en vez de posiciones numéricas se usan claves.
            $fila = $resultado->fetch_assoc();
            $hash_almacenado = $fila['Contraseña']; //Se guarda la contraseña en una variable.

            if ($contraseña === $hash_almacenado) { // Comparar contraseña de texto sin formato
                // Iniciar sesión y redirigir
                session_start();
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id_administrador'] = $fila['id_administrador']; // Guardar ID del admin
                header("Location: dashboard_admin.php");
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
        <h1>Inicie su sesión como administrador</h1>
        <form action="loginadmin.php" method="POST">
            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required><br><br>

            <label for="contraseña">Contraseña:</label><br>
            <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" required><br><br>

            <input type="submit" value="Iniciar Sesión">
        </form>
        <hr class="separator">
        <a href="paginalogins.html" class="link-volver">Regresar</a>
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
