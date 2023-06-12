/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/

TRUNCATE TABLE `RolesUsuario`;
TRUNCATE TABLE `Roles`;
TRUNCATE TABLE `Usuarios`;
TRUNCATE TABLE `Tratamientos`;

INSERT INTO `Roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'user');

INSERT INTO `Tratamientos` (`id`, `nombre`) VALUES
(1, 'Profesor'),
(2, 'profesora');


INSERT INTO `RolesUsuario` (`usuario`, `rol`) VALUES
(1, 1),
(1, 2),
(2, 2);

/*
  user: userpass
  admin: adminpass
*/
INSERT INTO `Usuarios` (`id`, `nombreUsuario`, `nombre`, `password`,`tratamiento_id`,`user_photo`) VALUES
(1, 'admin', 'Administrador', '$2y$10$O3c1kBFa2yDK5F47IUqusOJmIANjHP6EiPyke5dD18ldJEow.e0eS',2,'img/1.jpg'),
(2, 'user', 'Usuario', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG',1,'img/2.jpg'),
(3, 'antonio', 'antoniogp', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG',1,'img/3.jpg');

