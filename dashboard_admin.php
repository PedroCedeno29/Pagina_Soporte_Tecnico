<?php
// Iniciar sesión para obtener el mensaje
session_start();

// Conexión a la base de datos
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'solutions';

$conexion = new mysqli($server, $user, $pass, $db);

if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consultar los técnicos
//query(): Ejecuta directamente una consulta SQL en la base de datos. Ideal para consultas estáticas que no incluyen datos de entrada del usuario.
$query_tecnicos = "SELECT id_tecnico, Nombres, Especialidad, Estado FROM tecnicos";
$result_tecnicos = $conexion->query($query_tecnicos);

if (!$result_tecnicos) {
    die("Error en la consulta de técnicos: " . $conexion->error);
}

// Consultar los tickets
$query_tickets = "SELECT t.id_ticket, c.Nombres, c.Apellidos, t.tipo_problema, t.descripcion_problema, t.fecha_registro_ticket,t.Estado, t.laboratorio
                FROM tickets t
                INNER JOIN clientes c ON t.id_cliente = c.id_cliente
                WHERE t.Estado = 'Pendiente'";

$result_tickets = $conexion->query($query_tickets);

if (!$result_tickets) {
    die("Error en la consulta de tickets: " . $conexion->error);
}

// Obtener el mensaje de la sesión
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';

// Limpiar el mensaje de la sesión después de mostrarlo
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Administrador</title>
    <link rel="stylesheet" href="estilos/pagina_admin.css">  
</head>
<body>
    <div class="sidebar">
        <div class="admin-photo">
            <img src="imagenes/adminpedro.jpeg" alt="Foto del Administrador" />
        </div>
        <div class="admin-info">
            <h2>Bienvenido</h2>
            <h3>Administrador</h3>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard_admin.php">Página principal</a></li>
                <li><a href="admin_tickets_enproceso.php">Tickets en proceso</a></li>
                <li><a href="admin_tickets_finalizados.php">Tickets finalizados</a></li>
            </ul>
        </nav>
        <a href="cerrar_sesion.php" class="logout-btn">Cerrar sesión</a>
    </div>

    <div class="table-container">
        <h1>Técnicos</h1>
        <table>
            <thead>
                <tr>
                    <th>Técnicos</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los técnicos
                //Recupera cada fila del resultado como un array asociativo (clave: nombre de la columna, valor: dato).
                while ($row_tecnico = $result_tecnicos->fetch_assoc()) {
                    echo "<tr>";
                    // htmlspecialchars():Convierte caracteres especiales en entidades HTML (como < a &lt;).
                    //Previene ataques XSS asegurando que los datos mostrados no sean interpretados como código HTML o JavaScript.
                    echo "<td>" . htmlspecialchars($row_tecnico['Nombres']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_tecnico['Especialidad']) . "</td>";
                    echo "<td>" . htmlspecialchars($row_tecnico['Estado']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h1>Gestión de tickets pendientes</h1>
        <form method="POST" action="gestion_tickets.php">
            <table>
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Tipo de problema</th>
                        <th>Laboratorio</th>
                        <th>Descripción del problema</th>
                        <th>Estado</th>
                        <th>Técnicos</th>
                        <th>Fecha de registro</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar los tickets
                    while ($row_ticket = $result_tickets->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row_ticket['Nombres']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['Apellidos']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['tipo_problema']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['laboratorio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['descripcion_problema']) . "</td>";
                        echo "<td>" . htmlspecialchars($row_ticket['Estado']) . "</td>";

                        // Mostrar combobox de técnicos
                        echo "<td><select name='tecnico_{$row_ticket['id_ticket']}'>"; //nombre de la variable que almacena la tecnico. Ejemplo tecnico_1

                        // Resetear el puntero de los técnicos
                        $result_tecnicos->data_seek(0);

                        // Recorrer la lista de técnicos
                        while ($row_tecnico = $result_tecnicos->fetch_assoc()) {
                            //El valor del técnico que se seleccione del combobox va a ser su id, pero va a aparecer su nombre. Por eso va concatenado.
                            echo "<option value='{$row_tecnico['id_tecnico']}'>" . htmlspecialchars($row_tecnico['Nombres']) . "</option>";
                        }
                        echo "</select></td>";

                        echo "<td>" . htmlspecialchars($row_ticket['fecha_registro_ticket']) . "</td>";
                        echo "<td><input type='checkbox' name='tickets[]' value='{$row_ticket['id_ticket']}'></td>"; //El checkbox va a almacenar el id del ticket correspondiente a la fila que se seleccione.
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" name="asignar" value="asignar">Asignar Técnico(s)</button>
        </form>
    </div>

    <!-- Modal de mensaje -->
    <?php if ($mensaje): ?>
    <div id="mensajeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?php echo $mensaje; ?></p>
            <button id="btnCerrar" onclick="cerrarModal()">Cerrar</button>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Obtener el modal y el botón de cerrar
        var modal = document.getElementById("mensajeModal");
        var span = document.getElementsByClassName("close")[0];

        // Mostrar el modal si hay un mensaje
        if (modal) {
            modal.style.display = "block";
        }

        // Cuando el usuario hace clic en <span> (x), cierra el modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Función para cerrar el modal
        function cerrarModal() {
            modal.style.display = "none";
        }

        // Cuando el usuario hace clic en cualquier parte fuera del modal, también lo cierra
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>


<?php
// Cerrar la conexión
$conexion->close();
?>
