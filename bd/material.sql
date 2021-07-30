CREATE TABLE `material` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(250) DEFAULT NULL,
  `material` `descricao` TEXT NULL,
  `id_categoria` int(9) NOT NULL,
  `id_subcategoria` int(9) NOT NULL,
  `id_marca` int(9) NOT NULL,
  `id_linha` int(9) NOT NULL,
  `id_cor` int(9) NOT NULL,
  `quantidade` int(6) DEFAULT 0,
  `quantidade_venda` int(6) DEFAULT NULL,
  `tipo_venda` int(1) DEFAULT '1',
  `unidade_medida` int(1) NOT NULL,
  `largura` float(9,2) DEFAULT '0.00',
  `altura` float(9,2) DEFAULT '0.00',
  `id_espessura` INT(9) DEFAULT 0.00 NULL,
  `profundidade` float(9,2) DEFAULT '0.00',
  `peso` float(9,2) DEFAULT '0.00',
  `situacao_anuncio` int(1) DEFAULT '0',
  `id_usuario` int(9) NOT NULL,
  `dt_criacao` datetime DEFAULT NULL,
  `dt_modificado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_categoria` (`id_categoria`),
  KEY `fk_id_subcategoria` (`id_subcategoria`),
  KEY `fk_id_marca` (`id_marca`),
  KEY `fk_id_linha` (`id_linha`),
  KEY `fk_id_cor` (`id_cor`),
  CONSTRAINT `material_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `material_ibfk_2` FOREIGN KEY (`id_subcategoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `material_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  CONSTRAINT `material_ibfk_4` FOREIGN KEY (`id_linha`) REFERENCES `linha` (`id_linha`),
  CONSTRAINT `material_ibfk_5` FOREIGN KEY (`id_cor`) REFERENCES `cor` (`id_cor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `material_imagem` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `id_material` int(6) DEFAULT NULL,
  `nome_arquivo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pk_id_material` (`id_material`),
  CONSTRAINT `pk_id_material` FOREIGN KEY (`id_material`) REFERENCES `material` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

`

ALTER TABLE `material` CHANGE `espessura` `id_espessura` INT(9) DEFAULT 0.00 NULL; 

ALTER TABLE `material` ADD COLUMN `descricao` TEXT NULL AFTER `titulo`; 