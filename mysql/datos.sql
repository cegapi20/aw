/*
  Recuerda que deshabilitar la opción "Enable foreign key checks" para evitar problemas a la hora de importar el script.
*/
TRUNCATE TABLE `RolesUsuario`;
TRUNCATE TABLE `Roles`;
TRUNCATE TABLE `Usuarios`;
TRUNCATE TABLE `Mensajes`;

INSERT INTO `Roles` (`id`, `nombre`) VALUES
(1, 'user'),
(2, 'admin');


INSERT INTO `RolesUsuario` (`usuario`, `rol`) VALUES
(1, 1),
(1, 2),
(2, 1);

/*
  user@example.org: userpass
  admin@example.org: adminpass
*/


INSERT INTO `Usuarios` (`id`, `username`, `password`) VALUES
(1, 'admin@example.org', '$2y$10$O3c1kBFa2yDK5F47IUqusOJmIANjHP6EiPyke5dD18ldJEow.e0eS'),
(2, 'user@example.org', '$2y$10$uM6NtF.f6e.1Ffu2rMWYV.j.X8lhWq9l8PwJcs9/ioVKTGqink6DG');

SET @INICIO := NOW();
INSERT INTO `Mensajes` (`id`, `autor`, `mensaje`, `fechaHora`, `idMensajePadre`) VALUES
(1, 1, 'Bienvenido al foro', NOW(), NULL),
(2, 2, 'Muchas gracias', ADDTIME(@INICIO, '0:15:0'), 1),
(3, 2, 'Otra respuesta 1', ADDTIME(@INICIO, '0:16:0'), 1),
(4, 2, 'Otra respuesta 2', ADDTIME(@INICIO, '0:17:0'), 1),
(5, 2, 'Otra respuesta 3', ADDTIME(@INICIO, '0:17:3'), 1),
(6, 2, 'Otra respuesta 4', ADDTIME(@INICIO, '0:18:0'), 1),
(7, 2, 'Otra respuesta 5', ADDTIME(@INICIO, '0:21:0'), 1),
(8, 2, 'Otro mensaje', ADDTIME(@INICIO, '25:15:0'), NULL);
