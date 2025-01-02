<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generación de Ticket - Soluciones TecLab</title>
    <link rel="stylesheet" href="estilos/estilo_tickets.css">
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
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #45a049;
        }
          
        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; 
            z-index: -1; 
        }
    </style>
</head>
<body>

    <video class="background-video" autoplay muted loop>
        <source src="imagenes/principal/fondocliente.mp4" type="video/mp4">
        Tu navegador no soporta la reproducción de videos.
    </video>
    <div class="container">
        <h1>Generación de ticket para realizar el soporte técnico</h1>
        <form action="cliente_ticket.php" method="POST">
            <div class="row">
                <div>
                    <label for="laboratorio">Laboratorio:</label>
                    <input type="text" id="laboratorio" name="laboratorio" placeholder="Ingrese el laboratorio" required>
                </div>
                <div>
                    <label for="unidad">Número de Unidad:</label>
                    <input type="text" id="unidad" name="unidad" placeholder="Ingrese el número de computadora" required>
                    <br>
                </div>
                <div>
                    <label for="tipo_problema">Tipo de problema:</label>
                    <select name="tipo_problema" id="tipo_problema">
                        <option value="Problema de hardware">Problema de hardware</option>
                        <option value="Problema de software">Problema de software</option>
                        <option value="Problema físico">Problema físico</option>
                    </select>
                </div>
            </div>
            <label for="descripcion_problema">Describa el Problema:</label>
            <textarea id="descripcion_problema" name="descripcion_problema" placeholder="Detalle su problema aquí..." required></textarea>
            <input type="submit" value="Enviar Ticket">
        </form>

        <a href="ver_tickets.php" class="link-volver">Ver mis Tickets</a>
        <a href="index.html" class="link-volver">Volver a la Página Principal</a>
    </div>

    <!-- Modal de mensaje -->
    <?php 
    session_start();
    if (isset($_SESSION['mensaje'])): ?>
    <div id="mensajeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p><?php echo $_SESSION['mensaje']; ?></p>
            <button id="btnCerrar" onclick="cerrarModal()">Cerrar</button>
        </div>
    </div>
    <?php 
        // Limpiar el mensaje después de mostrarlo
        unset($_SESSION['mensaje']);
    ?>
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
