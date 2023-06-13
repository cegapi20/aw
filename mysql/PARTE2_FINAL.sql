-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2023 a las 16:29:12
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aw`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acronimostitulaciones`
--

CREATE TABLE `acronimostitulaciones` (
  `id` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `acronimostitulaciones`
--

INSERT INTO `acronimostitulaciones` (`id`, `nombre`) VALUES
('GIC', 'Grado de ingeniería de COMPUTADORES');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id` int(11) NOT NULL,
  `acronimo` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `acronimo_titulacion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id`, `acronimo`, `nombre`, `profesor_id`, `acronimo_titulacion`) VALUES
(1, 'SO', 'Sistemas Operativos', 4, 'GIC'),
(2, 'AC', 'Arquitectura de Computadores', 4, 'GIC'),
(3, 'AM', 'Ampliaci&oacute;n de Matem&aacute;ticas', 4, 'GIC'),
(4, 'Fal', 'Fundamentos algoritmicos', 4, 'GIC'),
(5, 'FP', 'Fundamentos PROGRAMACION', 3, 'GIC'),
(6, 'AW', 'aplicaciones web', 3, 'GII'),
(7, 'TS', 'testing de sofware', 3, 'GIC'),
(8, 'EDA', 'Estrucutara de computadores', 3, 'GIC'),
(9, 'aaaa', 'aaaaa', 3, 'GIC'),
(10, 'dddd', 'ddddaa', 3, 'GIC'),
(11, 'RB', 'ROBOTICA', 3, 'GIC');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantesclases`
--

CREATE TABLE `estudiantesclases` (
  `clase_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estudiantesclases`
--

INSERT INTO `estudiantesclases` (`clase_id`, `nombre`) VALUES
(1, 'Juan'),
(2, 'Juan'),
(2, 'Pedro'),
(11, 'ANDRE'),
(11, 'LEO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listasasistenciaestudiantes`
--

CREATE TABLE `listasasistenciaestudiantes` (
  `lista_asistencia_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listasasistencias`
--

CREATE TABLE `listasasistencias` (
  `id` int(11) NOT NULL,
  `clase_id` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolesusuario`
--

CREATE TABLE `rolesusuario` (
  `usuario` int(11) NOT NULL,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `rolesusuario`
--

INSERT INTO `rolesusuario` (`usuario`, `rol`) VALUES
(1, 1),
(1, 2),
(2, 2),
(4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamientos`
--

CREATE TABLE `tratamientos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tratamientos`
--

INSERT INTO `tratamientos` (`id`, `nombre`) VALUES
(1, 'Profesor'),
(2, 'Profesora');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombreUsuario` varchar(30) NOT NULL,
  `password` varchar(70) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tratamiento_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `password`, `nombre`, `tratamiento_id`) VALUES
(1, 'admin', '$2y$10$O3c1kBFa2yDK5F47IUqusOJmIANjHP6EiPyke5dD18ldJEow.e0eS', 'Administrador', 1),
(2, 'user', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG', 'Usuario', 2),
(3, 'antonio', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG', 'Profesor Antonio', 1),
(4, 'Maria', '$2y$10$yisB7y1yKlhcJyyXUQiiyORV/RtHY4clmRGQ0Tcglo7MDzyLt3vW2', 'María Garcia', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acronimostitulaciones`
--
ALTER TABLE `acronimostitulaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clases_FK_1` (`profesor_id`);

--
-- Indices de la tabla `estudiantesclases`
--
ALTER TABLE `estudiantesclases`
  ADD PRIMARY KEY (`clase_id`,`nombre`);

--
-- Indices de la tabla `listasasistencias`
--
ALTER TABLE `listasasistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forein` (`clase_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rolesusuario`
--
ALTER TABLE `rolesusuario`
  ADD PRIMARY KEY (`usuario`,`rol`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombreUsuario` (`nombreUsuario`),
  ADD KEY `tratamiento_id` (`tratamiento_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clases`
--
ALTER TABLE `clases`
  ADD CONSTRAINT `clases_FK_1` FOREIGN KEY (`profesor_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `estudiantesclases`
--
ALTER TABLE `estudiantesclases`
  ADD CONSTRAINT `estudiantesclases_FK` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`id`);

--
-- Filtros para la tabla `listasasistencias`
--
ALTER TABLE `listasasistencias`
  ADD CONSTRAINT `forein` FOREIGN KEY (`clase_id`) REFERENCES `clases` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tratamiento_id`) REFERENCES `tratamientos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
