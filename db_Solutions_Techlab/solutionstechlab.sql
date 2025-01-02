-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-01-2025 a las 17:54:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `solutions`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_administrador` int(11) NOT NULL,
  `Nombres` varchar(120) NOT NULL,
  `Apellidos` varchar(120) NOT NULL,
  `Correo` varchar(120) NOT NULL,
  `Telefono` varchar(15) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Contraseña` varchar(50) NOT NULL,
  `Fecha_creación` timestamp NOT NULL DEFAULT current_timestamp(),
  `Estado` varchar(50) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id_administrador`, `Nombres`, `Apellidos`, `Correo`, `Telefono`, `Usuario`, `Contraseña`, `Fecha_creación`, `Estado`) VALUES
(1, 'Pedro Jose', 'Cedeño Idrovo', 'pcedenoj29@gmail.com', '0979492122', 'pecedeno', 'pjcedeno2003', '2024-11-19 02:04:38', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `Nombres` varchar(120) NOT NULL,
  `Apellidos` varchar(120) NOT NULL,
  `Correo` varchar(120) NOT NULL,
  `Telefono` varchar(15) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Contraseña` varchar(50) NOT NULL,
  `Fecha_creación` timestamp NOT NULL DEFAULT current_timestamp(),
  `Estado` varchar(50) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `Nombres`, `Apellidos`, `Correo`, `Telefono`, `Usuario`, `Contraseña`, `Fecha_creación`, `Estado`) VALUES
(3, 'Josue Joel', 'Torres Cordero', 'josszet@gmail.com', '0987236184', 'josszet', 'jtorres21', '2024-11-19 02:07:15', 'A'),
(4, 'Kevin Nicolás', 'Moya Veas', 'kevintro@gmail.com', '0975437129', 'kevintro123', 'kevinjoel2004', '2024-11-20 15:41:44', 'A'),
(9, 'Fransua', 'Coronel', 'fcoronel@gmail.com', '0976512349', 'fcordero', 'fransua123', '2024-12-03 13:42:39', 'A'),
(10, 'Damian Nicolas', 'Lopez Galarza', 'dlopez@gmail.com', '0981146723', 'dlopez', 'damian123', '2024-12-04 12:36:07', 'A'),
(11, 'Joel', 'Moya Herrera', 'jmoya@gmail.com', '0988765512', 'jmoya', 'joel2004', '2024-12-04 13:38:48', 'A'),
(12, 'Mayerli', 'Yagual', 'myagual@gmail.com', '0993276114', 'myagual', 'mayerli123', '2024-12-08 03:59:17', 'A'),
(13, 'David', 'Chacon', 'dchacon@gmail.com', '0993276133', 'dchacon', 'david123', '2024-12-08 04:03:03', 'A'),
(14, 'Joel', 'Galarza', 'jgalarza@gmail.com', '0988764322', 'jgalarza', 'joel123', '2024-12-08 04:16:03', 'A'),
(15, 'Alfredo', 'Cruz', 'fcruz@gmail.com', '0975563342', 'acruz', 'alfredo123', '2024-12-08 15:37:40', 'A'),
(16, 'Matias', 'Villacis', 'mvillacis@gmail.com', '0996574332', 'mvillacis', 'matias123', '2024-12-08 15:41:27', 'A'),
(17, 'Sebastian', 'Llongo', 'sllongo@gmail.com', '0947623887', 'sllongo', 'sebastian123', '2024-12-08 15:42:54', 'A'),
(18, 'Sebastian', 'Galarza', 'sechin@gmail.com', '0965322441', 'sechin', 'sechin123', '2024-12-08 15:49:30', 'A'),
(19, 'Carlos', 'Valle', 'cvalle@gmail.com', '0987654321', 'cvalle', 'carlos123', '2024-12-11 12:59:20', 'A'),
(20, 'Olger', 'Coronel', 'ocoronel@gmail.com', '0987655433', 'coronelin', 'coronel123', '2024-12-11 15:31:48', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes`
--

CREATE TABLE `imagenes` (
  `id_imagen` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes`
--

INSERT INTO `imagenes` (`id_imagen`, `id_ticket`, `ruta_imagen`, `fecha_subida`) VALUES
(11, 13, 'imagenes/reparaciones/mouse.jpg', '2024-12-05 03:44:53'),
(12, 15, 'imagenes/reparaciones/pantalla dañada.webp', '2024-12-05 22:05:35'),
(13, 16, 'imagenes/reparaciones/teclado.jpg', '2024-12-07 23:19:15'),
(14, 19, 'imagenes/reparaciones/auriculares.jpg', '2024-12-08 01:48:07'),
(15, 22, 'imagenes/reparaciones/monitor.webp', '2024-12-08 04:29:29'),
(16, 23, 'imagenes/reparaciones/monitor.webp', '2024-12-08 04:58:37'),
(17, 25, 'imagenes/reparaciones/auriculares.jpg', '2024-12-08 15:31:28'),
(18, 26, 'imagenes/reparaciones/mouse 2.webp', '2024-12-08 16:09:03'),
(19, 28, 'imagenes/reparaciones/teclado.jpg', '2024-12-08 16:21:06'),
(20, 29, 'imagenes/reparaciones/auriculares.jpg', '2024-12-08 17:42:36'),
(21, 30, 'imagenes/reparaciones/teclado.jpg', '2024-12-08 17:56:10'),
(22, 31, 'imagenes/reparaciones/teclado.jpg', '2024-12-09 02:37:07'),
(23, 32, 'imagenes/reparaciones/teclado.jpg', '2024-12-09 11:57:51'),
(24, 35, 'imagenes/reparaciones/monitor.webp', '2024-12-10 16:26:32'),
(25, 37, 'imagenes/reparaciones/auriculares.jpg', '2024-12-11 13:17:04'),
(26, 38, 'imagenes/reparaciones/pantalla dañada.webp', '2024-12-11 15:34:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id_tecnico` int(11) NOT NULL,
  `Nombres` varchar(120) NOT NULL,
  `Apellidos` varchar(120) NOT NULL,
  `Correo` varchar(120) NOT NULL,
  `Telefono` varchar(15) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Contraseña` varchar(50) NOT NULL,
  `Fecha_creación` timestamp NOT NULL DEFAULT current_timestamp(),
  `Estado` varchar(50) NOT NULL DEFAULT 'A',
  `Especialidad` varchar(250) DEFAULT NULL,
  `foto_tecnico` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tecnicos`
--

INSERT INTO `tecnicos` (`id_tecnico`, `Nombres`, `Apellidos`, `Correo`, `Telefono`, `Usuario`, `Contraseña`, `Fecha_creación`, `Estado`, `Especialidad`, `foto_tecnico`) VALUES
(1, 'Alejandro Nicolas', 'Quevedo Tigua', 'aquevedo@gmail.com', '0991367592', 'aquevedo', 'alejandro2003', '2024-11-24 01:15:20', 'Libre', 'Reparación fisica', 'imagenes/tecnicos/foto_1.jpeg'),
(2, 'Guillermo', 'Velasco Anchundia', 'guvelasco@est.ecotec.edu.ec', '0983022724', 'guvelasco', 'guillermo123', '2024-11-27 13:57:43', 'Ocupado', 'Hardware', 'imagenes/tecnicos/foto_2.jpg'),
(3, 'Pedro', 'Cedeño Idrovo', 'pecedeno@est.ecotec.edu.ec', '0979492122', 'pecedeno', 'pjcedeno2003', '2024-11-27 13:58:40', 'Libre', 'Software', 'imagenes/tecnicos/foto_3.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id_ticket` int(11) NOT NULL,
  `laboratorio` varchar(50) NOT NULL,
  `numero_unidad` int(11) NOT NULL,
  `tipo_problema` varchar(250) NOT NULL,
  `descripcion_problema` varchar(250) NOT NULL,
  `fecha_registro_ticket` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_cliente` int(11) DEFAULT NULL,
  `Estado` varchar(250) DEFAULT 'Pendiente',
  `id_tecnico_asignado` int(11) DEFAULT NULL,
  `comentario` text DEFAULT 'No hubo comentarios'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `laboratorio`, `numero_unidad`, `tipo_problema`, `descripcion_problema`, `fecha_registro_ticket`, `id_cliente`, `Estado`, `id_tecnico_asignado`, `comentario`) VALUES
(3, 'Lab C3', 5, 'Problema físico', 'Dañaron el teclado.', '2024-11-27 13:44:26', 4, 'En proceso', NULL, 'No hubo comentarios'),
(6, 'Innovadata LAB-2', 12, 'Problema físico', 'Dañaron el mouse.', '2024-12-03 13:30:48', 3, 'Atendido', 1, 'No hubo comentarios'),
(7, 'Inspire Lab', 11, 'Problema de software', 'Sistema operativo lento.', '2024-12-03 13:48:58', 9, 'Atendido', 2, 'No hubo comentarios'),
(8, 'MediaLab ', 5, 'Problema físico', 'Dañaron la pantalla.', '2024-12-04 12:43:44', 10, 'Atendido', 1, 'No hubo comentarios'),
(9, 'Lab D10', 4, 'Problema físico', 'Pantalla rota.', '2024-12-04 13:39:35', 11, 'Atendido', 1, 'No hubo comentarios'),
(10, 'LAB AA', 32, 'Problema físico', 'Hicieron lona el monitor.', '2024-12-04 13:47:32', 11, 'Atendido', 1, 'No hubo comentarios'),
(11, 'LAB OB', 12, 'Problema físico', 'Dañaron la pantalla y el teclado.', '2024-12-04 13:58:09', 9, 'Atendido', 1, 'No hubo comentarios'),
(12, 'LAB WEST', 34, 'Problema físico', 'Dañaron el mouse.', '2024-12-05 03:25:17', 10, 'Atendido', 1, 'No hubo comentarios'),
(13, 'LAB LD', 21, 'Problema físico', 'El teclado esta dañado.', '2024-12-05 03:44:12', 10, 'Atendido', 1, 'No hubo comentarios'),
(14, 'LAB Engine', 10, 'Problema físico', 'Dañaron el monitor de la computadora. ', '2024-12-05 21:53:01', 10, 'Atendido', 1, 'No hubo comentarios'),
(15, 'Lab D200', 15, 'Problema físico', 'Dañaron el monitor. ', '2024-12-05 22:05:07', 9, 'Atendido', 1, 'Excelente servicio.'),
(16, 'Lab C3', 22, 'Problema físico', 'Dañaron el teclado.', '2024-12-05 22:07:36', 9, 'Atendido', 1, 'No hubo comentarios'),
(17, 'Lab A1', 11, 'Problema de software', 'Necesito instalar Cassandra en las computadoras del laboratorio. ', '2024-12-07 23:15:37', 9, 'Atendido', 2, 'No hubo comentarios'),
(18, 'Lab A3', 10, 'Problema físico', 'Dañaron el mouse de la computadora.', '2024-12-07 23:21:13', 10, 'Atendido', 1, 'Excelente servicio.'),
(19, 'LAB A4', 22, 'Problema físico', 'Dañaron los auriculares.', '2024-12-08 01:46:26', 3, 'Atendido', 2, 'No hubo comentarios'),
(20, 'LAB F2', 29, 'Problema físico', 'Destrozaron el mouse.', '2024-12-08 03:52:12', 9, 'Atendido', 1, 'No hubo comentarios'),
(21, 'LAB R1', 23, 'Problema físico', 'Dañaron la pantalla.', '2024-12-08 04:18:58', 13, 'Atendido', 2, 'No hubo comentarios'),
(22, 'LAB H4', 25, 'Problema físico', 'Dañaron el monitor de la computadora.', '2024-12-08 04:27:25', 12, 'Atendido', 1, 'No hubo comentarios'),
(23, 'LAB F4', 10, 'Problema físico', 'Monitor dañado.', '2024-12-08 04:32:14', 14, 'Atendido', 1, 'No hubo comentarios'),
(24, 'LAB J2', 5, 'Problema físico', 'Dañaron el mouse.', '2024-12-08 05:00:23', 12, 'Atendido', 2, 'No hubo comentarios'),
(25, 'LAB N1', 14, 'Problema físico', 'Dañaron los auriculares.', '2024-12-08 15:21:06', 14, 'Atendido', 1, 'No hubo comentarios'),
(26, 'LAB J3', 16, 'Problema físico', 'Dañaron el mouse.', '2024-12-08 16:05:50', 12, 'Atendido', 1, 'Excelente servicio.'),
(27, 'LAB T1', 17, 'Problema físico', 'Dañaron el teclado.', '2024-12-08 16:14:57', 12, 'Atendido', 1, 'No hubo comentarios'),
(28, 'LAB U2', 12, 'Problema físico', 'Dañaron el teclado.', '2024-12-08 16:20:26', 16, 'Atendido', 1, 'No hubo comentarios'),
(29, 'LAB M1', 17, 'Problema físico', 'Dañaron los auriculares.', '2024-12-08 16:39:17', 16, 'Atendido', 1, 'No hubo comentarios'),
(30, 'LAB K1', 11, 'Problema físico', 'Dañaron el teclado.', '2024-12-08 17:46:25', 18, 'Atendido', 1, 'No hubo comentarios'),
(31, 'LAB K4', 5, 'Problema físico', 'Dañaron el teclado.', '2024-12-09 02:34:58', 9, 'Atendido', 1, 'No hubo comentarios'),
(32, 'LAB T2', 12, 'Problema físico', 'Dañaron el teclado.', '2024-12-09 11:56:07', 10, 'Atendido', 1, 'Excelente servicio.'),
(33, 'LAB N2', 9, 'Problema de software', 'No se instalo SQL Server en las computadoras.', '2024-12-09 12:37:42', 18, 'Atendido', 2, 'No hubo comentarios'),
(34, 'LAB M3', 8, 'Problema de hardware', 'Necesito que instalen Cassandra en las máquinas urgente.', '2024-12-09 12:51:02', 9, 'En pausa', 2, 'No hubo comentarios'),
(35, 'Lab C3', 33, 'Problema físico', 'Dañaron el monitor', '2024-12-09 13:45:10', 3, 'Atendido', 1, 'No hubo comentarios'),
(36, 'LAB INNOVADATA', 3, 'Problema físico', 'Dañaron el teclado.', '2024-12-11 13:00:24', 19, 'Atendido', 1, 'excelente servicio'),
(37, 'LAB INNOVADATA', 2, 'Problema físico', 'Dañaron los auriculares.', '2024-12-11 13:15:40', 19, 'Atendido', 1, 'No hubo comentarios'),
(38, 'LAB B1', 1, 'Problema físico', 'Dañaron un monitor.', '2024-12-11 15:32:37', 20, 'Atendido', 1, 'Excelente servicio quevedin.');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_administrador`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_ticket` (`id_ticket`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id_tecnico`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `fk_id_cliente` (`id_cliente`),
  ADD KEY `fk_id_tecnico_asignado` (`id_tecnico_asignado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `imagenes`
--
ALTER TABLE `imagenes`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id_tecnico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imagenes`
--
ALTER TABLE `imagenes`
  ADD CONSTRAINT `imagenes_ibfk_1` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`);

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_id_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `fk_id_tecnico_asignado` FOREIGN KEY (`id_tecnico_asignado`) REFERENCES `tecnicos` (`id_tecnico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
