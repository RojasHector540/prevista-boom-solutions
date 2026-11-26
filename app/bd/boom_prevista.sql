-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-11-2026 a las 01:15:40
-- Versión del servidor: 10.11.16-MariaDB
-- Versión de PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `boom_prevista`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus_usuario`
--

CREATE TABLE `estatus_usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estatus_usuario`
--

INSERT INTO `estatus_usuario` (`id`, `nombre`) VALUES
(1, 'Activo'),
(3, 'Eliminado'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_seguridad`
--

CREATE TABLE `preguntas_seguridad` (
  `id` int(11) NOT NULL,
  `pregunta` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `preguntas_seguridad`
--

INSERT INTO `preguntas_seguridad` (`id`, `pregunta`, `status`) VALUES
(1, '¿Cuál es el nombre de tu primera mascota?', 1),
(2, '¿En qué ciudad naciste?', 1),
(3, '¿Cuál es el nombre de tu comida favorita?', 1),
(4, '¿Cómo se llamaba tu escuela primaria?', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `status`) VALUES
(1, 'admin', 'Administrador General', 1),
(2, 'cs', 'Servicio al Cliente', 1),
(3, 'aprocoord', 'Aprobaciones y Coordinaciones', 1),
(4, 'ventas', 'Ventas', 1),
(5, 'noc', 'Monitoring / NOC', 1),
(6, 'lider_zona', 'Líder de Zona', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(205) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `estatus_id` int(11) NOT NULL,
  `pregunta_id` int(11) DEFAULT NULL,
  `respuesta_seguridad` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `usuario`, `correo`, `contraseña`, `rol_id`, `estatus_id`, `pregunta_id`, `respuesta_seguridad`, `creado_en`) VALUES
(1, 'Admin', 'Boom', 'admin', '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NULL, NULL, '2026-11-19 20:48:01'),
(2, 'Hector', 'Rojas', 'HECTOR.CS908', 'rojashector540@gmail.com', '$2y$10$f.n4mK0txjh8CB7boLeiTuT8/iiIScLomGIi0t1AGX7iIBN5OAfA.', 1, 1, 1, '$2y$10$sg/QwmY14ahTogEqoxJcVO/CBgAVZTJUFDYTPhNAHIK4HPy8XqfCm', '2026-11-19 21:21:16'),
(3, 'Andrea', 'Suarez', 'ANDREA.CS924', 'suarez.gimenez17@gmail.com', '$2y$10$SWDwFmAvfKZrG6rHN/NMRui7rWe6mxojmp/k3yPWSt.o3QlbP4LUm', 2, 1, 2, '$2y$10$oJLCEoWwDkHBQcMdJx532uDbRpuNfXsjt91fCLqECarZuvmSj2kPK', '2026-11-19 21:34:57');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estatus_usuario`
--
ALTER TABLE `estatus_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pregunta` (`pregunta`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `fk_usuarios_rol` (`rol_id`),
  ADD KEY `fk_usuarios_estatus` (`estatus_id`),
  ADD KEY `fk_usuarios_pregunta` (`pregunta_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estatus_usuario`
--
ALTER TABLE `estatus_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_estatus` FOREIGN KEY (`estatus_id`) REFERENCES `estatus_usuario` (`id`),
  ADD CONSTRAINT `fk_usuarios_pregunta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas_seguridad` (`id`),
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
