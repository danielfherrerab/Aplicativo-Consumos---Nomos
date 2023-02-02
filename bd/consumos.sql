CREATE DATABASE IF NOT EXISTS `consumos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `consumos`;

CREATE TABLE `usuarios` (
  `id_usuario` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(40) not null,
  `correo` varchar(60) not null,
  `clave` varchar(100) not null,
  `consumo_planchas` enum("si","no") DEFAULT "no",
  `ubicaciones` enum("si","no") DEFAULT "no",
  `inventario` enum("si","no") DEFAULT "no",
  `informe_consumo` enum("si","no") DEFAULT "no",
  `usuarios` enum("si","no") DEFAULT "no",
  `importar_ordenes` enum("si","no")DEFAULT "no",
  `corte_consumos` enum("si","no") DEFAULT "no"
);

CREATE TABLE `articulos` (
  `id_articulo` int PRIMARY KEY AUTO_INCREMENT,
  `descripcion` varchar(100) not null,
  `medida` varchar(50) not null,
  `codigo_articulo` varchar(100) not null,
  `estado` enum("activo","inactivo") DEFAULT "activo"
);

INSERT INTO `articulos` (`id_articulo`, `descripcion`, `medida`, `codigo_articulo`, `estado`) VALUES 
  (1, 'PLANCHAS ECLIPSE 1030 X 790', '1030 X 790', '48019110010405', 'activo'),
  (2, 'PLANCHAS KODAK SWORDMAX 1030 X 790', '1030 X 790', '48019010010390', 'activo'),
  (3, 'PLANCHAS KODAK CAPRICORN 760 X 605', '760 X 605', '48018900010360', 'activo'),
  (4, 'PLANCHAS KODAK CAPRICORN GT 1030 X 800', '1030 X 800', '48018800010359', 'activo'),
  (5, 'PLANCHAS KODAK CAPRICORN 945 X 700', '945 X 700', '48018700010361', 'activo'),
  (6, 'PLANCHAS KODAK CAPRICORN GT 1030 X 790', '1030 X 790', '48018500010336', 'activo'),
  (7, 'PLANCHAS AZURA 1030 X 800', '1030 X 800', '48018200010256', 'activo'),
  (8, 'PLANCHAS AZURA 1030 X 790', '1030 X 790', '48017800010252', 'activo'),
  (9, 'PLANCHAS AZURA 945 X 700', '945 X 700', '48017400010248', 'activo'),
  (10, 'PLANCHAS AZURA 760 X 605', '760 X 605', '48017200010246', 'activo');    

CREATE TABLE `ordenes` (
  `numero_op` int PRIMARY KEY,
  `nombre_trabajo` varchar(255) not null,
  `fecha` date not null,
  `cp` varchar(10) not null,
  `nombre_centro` varchar(50) not null,
  `cantidad_planeada` int not null DEFAULT 0
);

CREATE TABLE `consumo_planchas` (
  `id_consumo` int PRIMARY KEY AUTO_INCREMENT,
  `id_usuario` int not null,
  `numero_op` int not null,
  `id_articulo` int not null,
  `descripcion` varchar(100) not null,
  `fecha_consumo` datetime not null,
  `observacion` varchar(100) not null,
  `id_ubicacion` int not null,
  `cantidad` int not null DEFAULT 0,
  `marcador` tinyint(1) NOT NULL DEFAULT 0
);

CREATE TABLE `ubicaciones` (
  `id_ubicacion` int PRIMARY KEY AUTO_INCREMENT,
  `ubicacion` varchar(255) not null,
  `estado` enum("activo","inactivo") DEFAULT "activo" NOT NULL
);

-- CREATE TABLE `tabla_consumo` (
--   `id_articulo` int,
--   `enero` int NOT NULL DEFAULT 0,
--   `febrero` int NOT NULL DEFAULT 0,
--   `marzo` int NOT NULL DEFAULT 0,
--   `abril` int NOT NULL DEFAULT 0,
--   `mayo` int NOT NULL DEFAULT 0,
--   `junio` int NOT NULL DEFAULT 0,
--   `julio` int NOT NULL DEFAULT 0,
--   `agosto` int NOT NULL DEFAULT 0,
--   `septiembre` int NOT NULL DEFAULT 0,
--   `octubre` int NOT NULL DEFAULT 0,
--   `noviembre` int NOT NULL DEFAULT 0,
--   `diciembre` int NOT NULL DEFAULT 0
-- );

CREATE TABLE `inventario_meses` (
  `id_articulo` int not null,
  `enero` int NOT NULL DEFAULT 0,
  `febrero` int NOT NULL DEFAULT 0,
  `marzo` int NOT NULL DEFAULT 0,
  `abril` int NOT NULL DEFAULT 0,
  `mayo` int NOT NULL DEFAULT 0,
  `junio` int NOT NULL DEFAULT 0,
  `julio` int NOT NULL DEFAULT 0,
  `agosto` int NOT NULL DEFAULT 0,
  `septiembre` int NOT NULL DEFAULT 0,
  `octubre` int NOT NULL DEFAULT 0,
  `noviembre` int NOT NULL DEFAULT 0,
  `diciembre` int NOT NULL DEFAULT 0
);

INSERT INTO `inventario_meses` VALUES
  (1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
  (10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

CREATE TABLE `inventario` (
  `id_inventario` int PRIMARY KEY AUTO_INCREMENT,
  `id_ubicacion` int not null,
  `id_articulo` int not null,
  `fecha_ingreso` datetime not null,
  `descripcion` varchar(100) not null,
  `nota` varchar(100) not null,
  `cantidad` int not null default 0
);

CREATE TABLE `corte_consumos` (
  `id_corte` int PRIMARY KEY AUTO_INCREMENT,
  `fecha_corte` varchar(7) not null,
  `estado` enum("bloqueado","disponible") DEFAULT "disponible"
);

INSERT INTO `corte_consumos`  VALUES(
  1,"2023-01","disponible"
  2,"2023-02","disponible"
);

CREATE TABLE `tabla_inventario` (
  `id_articulo` int(11) NOT NULL,
  `1` int(11) NOT NULL DEFAULT 0,
  `2` int(11) NOT NULL DEFAULT 0,
  `3` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tabla_inventario` VALUES
  (1, 0, 0, 0),
  (2, 0, 0, 0),
  (3, 0, 0, 0),
  (4, 0, 0, 0),
  (5, 0, 0, 0),
  (6, 0, 0, 0),
  (7, 0, 0, 0),
  (8, 0, 0, 0),
  (9, 0, 0, 0),
  (10, 0, 0, 0);

CREATE TABLE `tabla_recuento` (
  `id_articulo` int(11) DEFAULT NULL,
  `consumo` int(11) NOT NULL default '0',
  `inventario` int(11) NOT NULL default '0',
  `resultado` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tabla_consumo2023-01` (
  `id_articulo` int(11) DEFAULT NULL,
  `consumo` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tabla_consumo2023-01` VALUES
  (1, 0),
  (2, 0),
  (3, 0),
  (4, 0),
  (5, 0),
  (6, 0),
  (7, 0),
  (8, 0),
  (9, 0),
  (10, 0);

ALTER TABLE `tabla_consumo2023-01` ADD KEY `id_articulo` (`id_articulo`);

ALTER TABLE `tabla_consumo2023-01` ADD CONSTRAINT `tabla_consumo2023-01_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);

/* TABLA DE FEBRERO */
CREATE TABLE `tabla_consumo2023-02` (
  `id_articulo` int(11) DEFAULT NULL,
  `consumo` int(11) NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tabla_consumo2023-02` VALUES
  (1, 0),
  (2, 0),
  (3, 0),
  (4, 0),
  (5, 0),
  (6, 0),
  (7, 0),
  (8, 0),
  (9, 0),
  (10, 0);

ALTER TABLE `tabla_consumo2023-02` ADD KEY `id_articulo` (`id_articulo`);

ALTER TABLE `tabla_consumo2023-02` ADD CONSTRAINT `tabla_consumo2023-02_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);

INSERT INTO `tabla_recuento` VALUES
  (1, 0, 0, 0),
  (2, 0, 0, 0),
  (3, 0, 0, 0),
  (4, 0, 0, 0),
  (5, 0, 0, 0),
  (6, 0, 0, 0),
  (7, 0, 0, 0),
  (8, 0, 0, 0),
  (9, 0, 0, 0),
  (10, 0, 0, 0);

INSERT INTO `ubicaciones` (`id_ubicacion`, `ubicacion`, `estado`) VALUES
(1, 'nomos', 'activo'),
(2, 'premedia', 'activo'),
(3, 'xpress', 'activo');

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `clave`, `consumo_planchas`, `ubicaciones`, `inventario`, `informe_consumo`, `usuarios`, `importar_ordenes`, `corte_consumos`) VALUES
(1, 'administrador', 'admin@admin.com', '$2y$10$uTKllsApT0PpxyOLbuHT8./dfn0ZpJweJiJDFnlhBNdamSfQigR42', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(2, 'juan', 'juan@gmail.com', '$2y$10$fFjnYzinI879wwILbq28luRQ.ZEnyEgpi06FknCkTUVuNUHdYahBu', 'no', 'no', 'no', 'si', 'no', 'no', 'no');


ALTER TABLE `tabla_recuento` ADD KEY `id_articulo` (`id_articulo`);

ALTER TABLE `consumo_planchas` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

ALTER TABLE `consumo_planchas` ADD FOREIGN KEY (`numero_op`) REFERENCES `ordenes` (`numero_op`);

ALTER TABLE `consumo_planchas` ADD FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);

ALTER TABLE `consumo_planchas` ADD FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id_ubicacion`);

-- ALTER TABLE `tabla_consumo` ADD FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);

ALTER TABLE `tabla_inventario` ADD FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);

ALTER TABLE `inventario` ADD FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id_ubicacion`);

ALTER TABLE `inventario` ADD FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);

ALTER TABLE `tabla_recuento` ADD CONSTRAINT `tabla_recuento_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`);
