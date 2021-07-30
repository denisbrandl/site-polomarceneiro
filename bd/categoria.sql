DROP TABLE IF EXISTS categoria;
CREATE TABLE `categoria` (
  `id_categoria` INT(9) NOT NULL AUTO_INCREMENT,  
  `id_categoria_pai` INT(9) NOT NULL DEFAULT 0,  
  `descricao` VARCHAR(150) DEFAULT NULL,
  `situacao` INT(1) DEFAULT NULL,
  `dt_criacao` DATETIME DEFAULT NULL,
  `dt_modificado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id_categoria`),
  KEY `id_categoria_pai` (`id_categoria_pai`)
) ENGINE=INNODB DEFAULT CHARSET=utf8
;


truncate categoria;
	INSERT INTO categoria VALUES 
(0, 0, 'MDF', 1, NOW(), NOW()),
(0, 1, 'MDF Branco', 1, NOW(), NOW()),
(0, 1, 'MDF Cru', 1, NOW(), NOW()),
(0, 1, 'MDF Madeirado', 1, NOW(), NOW()),
(0, 1, 'MDF Unicores', 1, NOW(), NOW()),
(0, 0, 'MDP', 1, NOW(), NOW()),
(0, 6, 'MDP Cru', 1, NOW(), NOW()),
(0, 6, 'MDP Unicores', 1, NOW(), NOW()),
(0, 0, 'Acabamentos', 1, NOW(), NOW()),
(0, 9, 'Colas e Adesivos', 1, NOW(), NOW()),
(0, 9, 'Imunizantes', 1, NOW(), NOW()),
(0, 9, 'Lacas', 1, NOW(), NOW()),
(0, 9, 'Retoques', 1, NOW(), NOW()),
(0, 9, 'Seladoras, Vernizes e Tingidores', 1, NOW(), NOW()),
(0, 9, 'Solventes e Limpadores', 1, NOW(), NOW()),
(0, 9, 'Lixas', 1, NOW(), NOW()),
(0, 9, 'Cinta Lixa', 1, NOW(), NOW());
