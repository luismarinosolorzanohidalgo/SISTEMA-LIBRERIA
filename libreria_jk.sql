-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-12-2025 a las 19:33:51
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
-- Base de datos: `libreria_jk`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacen`
--

CREATE TABLE `almacen` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `proveedor` varchar(120) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `precio_compra` decimal(10,2) DEFAULT 0.00,
  `precio_venta` decimal(10,2) DEFAULT 0.00,
  `fecha_ingreso` date NOT NULL,
  `estado` enum('Disponible','Agotado','Inactivo') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `almacen`
--

INSERT INTO `almacen` (`id`, `codigo`, `nombre`, `categoria`, `proveedor`, `stock`, `precio_compra`, `precio_venta`, `fecha_ingreso`, `estado`) VALUES
(1, 'PROD-001', 'Cuaderno universitario 100 hojas', 'Cuadernos', 'AURORA', 0, 3.50, 6.00, '2025-11-05', 'Disponible'),
(2, 'PROD-002', 'Lápiz grafito HB', 'Escritura', 'FABER CASTELL', 98, 0.80, 1.50, '2025-11-05', 'Disponible'),
(3, 'PROD-003', 'Lapicero azul BIC', 'Escritura', 'BIC PERÚ', 120, 0.90, 1.80, '2025-11-05', 'Disponible'),
(4, 'PROD-004', 'Resaltador amarillo', 'Escritura', 'STABILO', 60, 1.20, 2.50, '2025-11-05', 'Disponible'),
(5, 'PROD-005', 'Corrector líquido', 'Oficina', 'PILOT', 30, 2.00, 3.50, '2025-11-05', 'Disponible'),
(6, 'PROD-006', 'Tajador metálico doble', 'Accesorios', 'MAPED', 50, 1.50, 2.80, '2025-11-05', 'Disponible'),
(7, 'PROD-007', 'Borrador blanco', 'Accesorios', 'FABER CASTELL', 80, 0.60, 1.20, '2025-11-05', 'Disponible'),
(8, 'PROD-008', 'Regla 30 cm transparente', 'Medición', 'STABILO', 40, 1.00, 2.00, '2025-11-05', 'Disponible'),
(9, 'PROD-009', 'Caja de colores 12 unidades', 'Arte', 'ARTESCO', 25, 6.00, 9.50, '2025-11-05', 'Disponible'),
(10, 'PROD-010', 'Témpera 6 colores', 'Arte', 'VINIFAN', 20, 4.50, 7.00, '2025-11-05', 'Disponible'),
(11, 'PROD-011', 'Pegamento en barra 21g', 'Accesorios', 'PRITT', 45, 2.80, 4.50, '2025-11-05', 'Disponible'),
(12, 'PROD-012', 'Tijera escolar punta roma', 'Accesorios', 'ARTESCO', 35, 3.00, 5.50, '2025-11-05', 'Disponible'),
(13, 'PROD-013', 'Mochila escolar mediana', 'Mochilas', 'TOTTO', 9, 45.00, 65.00, '2025-11-05', 'Disponible'),
(14, 'PROD-014', 'Cartulina tamaño A4', 'Papelería', 'RIVERA', 200, 0.50, 1.00, '2025-11-05', 'Disponible'),
(15, 'PROD-015', 'Folder manila oficio', 'Papelería', 'VINIFAN', 150, 0.70, 1.30, '2025-11-05', 'Disponible'),
(16, 'PROD-016', 'Grapas N°10', 'Oficina', 'KANGARO', 100, 1.20, 2.00, '2025-11-05', 'Disponible'),
(17, 'PROD-017', 'Engrapadora metálica pequeña', 'Oficina', 'KANGARO', 25, 9.00, 14.50, '2025-11-05', 'Disponible'),
(18, 'PROD-018', 'Cinta adhesiva transparente 18mm', 'Papelería', 'VINIFAN', 70, 2.00, 3.50, '2025-11-05', 'Disponible'),
(19, 'PROD-019', 'Cuaderno cuadriculado 80 hojas', 'Cuadernos', 'NORMA', 32, 2.80, 5.00, '2025-11-05', 'Disponible'),
(20, 'PROD-020', 'Papel bond A4 (resma)', 'Papelería', 'CHAMEX', 21, 18.00, 25.00, '2025-11-05', 'Disponible'),
(27, 'BOL-21', 'Stanford A4 100 hojas Cuadriculado', 'Cuadernos', 'LUIS SOLORZANO HIDALGO', 1, 0.00, 0.00, '2025-11-06', 'Disponible'),
(30, 'BOL-21', 'Stanford A4 100 hojas Cuadriculado', 'Cuadernos', 'LUIS SOLORZANO HIDALGO', 2, 0.00, 0.00, '2025-11-08', 'Disponible'),
(31, 'PROD-001', 'Cuaderno universitario 100 hojas', 'Cuadernos', 'AURORA', 1, 3.50, 6.00, '2025-11-08', 'Disponible'),
(32, 'PROD-001', 'Cuaderno universitario 100 hojas', 'Cuadernos', 'AURORA', 1, 3.50, 6.00, '2025-11-08', 'Disponible'),
(33, 'PROD-001', 'Cuaderno universitario 100 hojas', 'Cuadernos', 'AURORA', 2, 3.50, 6.00, '2025-11-08', 'Disponible'),
(34, 'PROD-013', 'Mochila escolar mediana', 'Mochilas', 'TOTTO', 20, 45.00, 65.00, '2025-11-08', 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id` int(10) UNSIGNED NOT NULL,
  `empleado_id` int(10) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `entrada` time DEFAULT NULL,
  `salida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id`, `empleado_id`, `fecha`, `entrada`, `salida`) VALUES
(1, 3, '2025-11-04', '20:30:44', '20:39:05'),
(2, 5, '2025-11-04', '20:41:32', '02:45:38'),
(3, 1, '2025-11-04', '02:45:33', '02:56:21'),
(4, 4, '2025-11-04', '02:51:03', '02:56:23'),
(5, 2, '2025-11-04', '02:53:28', '02:54:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(3, 2, 13, 1, 65.00, 65.00),
(4, 3, 1, 1, 6.00, 6.00),
(5, 4, 2, 2, 1.50, 3.00),
(6, 5, 19, 1, 5.00, 5.00),
(7, 6, 19, 2, 5.00, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cargo` enum('Administrador','Vendedor','Almacén') NOT NULL,
  `correo` varchar(150) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellido`, `cargo`, `correo`, `telefono`, `fecha_creacion`) VALUES
(1, 'Luis', 'Solórzano', 'Administrador', 'luis.solorzano@example.com', '987654321', '2025-11-04 00:52:30'),
(2, 'Maria', 'Quito', 'Administrador', 'maria.quito@example.com', '987123456', '2025-11-04 00:52:30'),
(3, 'Alejo', 'Evangelista', 'Vendedor', 'alejo.evangelista@example.com', '987111222', '2025-11-04 00:52:30'),
(4, 'María', 'Pérez', 'Almacén', 'maria.perez@example.com', '987333444', '2025-11-04 00:52:30'),
(5, 'Juan', 'González', 'Almacén', 'juan.gonzalez@example.com', '987555666', '2025-11-04 00:52:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados_horario`
--

CREATE TABLE `empleados_horario` (
  `id` int(11) NOT NULL,
  `empleado_id` int(10) UNSIGNED NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados_horario`
--

INSERT INTO `empleados_horario` (`id`, `empleado_id`, `hora_inicio`, `hora_fin`) VALUES
(1, 4, '20:50:00', '20:52:00'),
(2, 2, '20:52:00', '20:55:00'),
(3, 3, '20:10:00', '08:20:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria`, `nombre`, `descripcion`, `precio`) VALUES
(1, 'Cuadernos', 'Stanford', 'A4 100 hojas - cuadriculado', 8.50),
(2, 'Cuadernos', 'Stanford', 'A4 100 hojas - rayado', 8.50),
(3, 'Cuadernos', 'Alpha', 'Pequeño 50 hojas - cuadriculado', 4.00),
(4, 'Cuadernos', 'Conti', 'A4 100 hojas - triple renglón', 7.80),
(5, 'Cuadernos', 'Inbox', 'Pequeño 50 hojas - rayado', 4.20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) DEFAULT 'Administrador del Sistema',
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` varchar(30) DEFAULT 'Administrador',
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre_completo`, `email`, `telefono`, `rol`, `estado`, `fecha_registro`, `fecha_creacion`) VALUES
(1, 'Luis Marino', '$2y$10$CblLtktr3cmz2toWIxwnCOCEyHIYl.k0u96Tf8V5dFHjOtLbXBLgq', 'Administrador Principal', 'admin@gmail.com', '978456123', 'Administrador', 'Activo', '2025-11-05 22:59:02', '2025-11-05 18:58:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente` varchar(100) DEFAULT NULL,
  `dni` varchar(15) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente`, `dni`, `fecha`, `total`) VALUES
(2, 'LEONARDO NAVARRO SAGARDIA', '61003734', '2025-11-10 11:33:28', 65.00),
(3, 'LEONARDO NAVARRO SAGARDIA', '61003734', '2025-11-10 11:34:15', 6.00),
(4, 'LEONARDO NAVARRO SAGARDIA', '61003734', '2025-11-10 11:35:26', 3.00),
(5, 'LEONARDO NAVARRO SAGARDIA', '61003734', '2025-11-10 12:14:03', 5.00),
(6, 'LEONARDO NAVARRO SAGARDIA', '61003734', '2025-11-10 12:37:45', 10.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacen`
--
ALTER TABLE `almacen`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empleado` (`empleado_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `detalle_venta_ibfk_2` (`producto_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `empleados_horario`
--
ALTER TABLE `empleados_horario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empleado_horario` (`empleado_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacen`
--
ALTER TABLE `almacen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `empleados_horario`
--
ALTER TABLE `empleados_horario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `almacen` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados_horario`
--
ALTER TABLE `empleados_horario`
  ADD CONSTRAINT `fk_empleado_horario` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
