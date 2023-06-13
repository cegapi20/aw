/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
TRUNCATE TABLE `RolesUsuario`;
TRUNCATE TABLE `Roles`;
TRUNCATE TABLE `Usuarios`;

SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE TABLE `Tratamientos`;
SET FOREIGN_KEY_CHECKS = 1;


INSERT INTO `Roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'user');


INSERT INTO `RolesUsuario` (`usuario`, `rol`) VALUES
(1, 1),
(1, 2),
(2, 2);

INSERT INTO `Tratamientos` (`id`, `nombre`) VALUES
(1, 'Profesor'),
(2, 'Profesora');


/*
  user: userpass
  admin: adminpass
*/
INSERT INTO `Usuarios` (`id`, `nombreUsuario`, `nombre`, `password`, `tratamiento_id`) VALUES
(1, 'admin', 'Administrador', '$2y$10$O3c1kBFa2yDK5F47IUqusOJmIANjHP6EiPyke5dD18ldJEow.e0eS',1),
(3, 'antonio', 'Profesor Antonio', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG',1),
(2, 'user', 'Usuario', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG',2);

