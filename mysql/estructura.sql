/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
DROP TABLE IF EXISTS `RolesUsuario`;
DROP TABLE IF EXISTS `Roles`;
DROP TABLE IF EXISTS `ListasAsistenciaEstudiantes`;
DROP TABLE IF EXISTS `EstudiantesClase`;
DROP TABLE IF EXISTS `ListasAsistencia`;
DROP TABLE IF EXISTS `Clases`;
DROP TABLE IF EXISTS `Usuarios`;
DROP TABLE IF EXISTS `Tratamientos`;

CREATE TABLE IF NOT EXISTS `Roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `RolesUsuario` (
  `usuario` int(11) NOT NULL,
  `rol` int(11) NOT NULL,
  PRIMARY KEY (`usuario`,`rol`),
  KEY `rol` (`rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `Tratamientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `Usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(30) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  `password` varchar(70) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tratamiento_id` int,
   `user_photo` varchar(255) NOT NULL,
   FOREIGN KEY (`tratamiento_id`) REFERENCES `Tratamientos`(`id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `Clases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acronimo` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `acronimoTitulacion` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `profesor_id` int  NOT NULL,
  FOREIGN KEY (`profesor_id`) REFERENCES `Usuarios`(`id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `EstudiantesClase` (
  `clase_id` int NOT NULL,
   `nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
  FOREIGN KEY (`clase_id`) REFERENCES `Clases`(`id`),
  PRIMARY KEY (`clase_id`,`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ListasAsistencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clase_id` int  NOT NULL,
  `fecha` DATE not null,
  FOREIGN KEY (`clase_id`) REFERENCES `Clases`(`id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `ListasAsistenciaEstudiantes` (
  `lista_asistencia_id` int  NOT NULL,
  `nombre` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  FOREIGN KEY (`lista_asistencia_id`) REFERENCES `ListasAsistencia`(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
