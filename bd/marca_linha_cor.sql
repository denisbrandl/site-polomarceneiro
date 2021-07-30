DROP TABLE IF EXISTS cor;
DROP TABLE IF EXISTS linha;
DROP TABLE IF EXISTS marca;
CREATE TABLE `marca` (
  `id_marca` INT(9) NOT NULL AUTO_INCREMENT,  
  `descricao` VARCHAR(150) DEFAULT NULL,
  `situacao` INT(1) DEFAULT NULL,
  `dt_criacao` DATETIME DEFAULT NULL,
  `dt_modificado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=INNODB DEFAULT CHARSET=utf8
;


CREATE TABLE `linha` (
  `id_linha` INT(9) NOT NULL AUTO_INCREMENT,  
  `descricao` VARCHAR(150) DEFAULT NULL,
  `situacao` INT(1) DEFAULT NULL,
  `id_marca` INT(9) DEFAULT NULL,
  `dt_criacao` DATETIME DEFAULT NULL,
  `dt_modificado` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id_linha`),
  FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8
;


CREATE TABLE `cor` (
  `id_cor` INT(9) NOT NULL AUTO_INCREMENT,  
  `descricao` VARCHAR(150) DEFAULT NULL,
  `situacao` INT(1) DEFAULT NULL,
  `id_linha` INT(9) DEFAULT NULL,
  `dt_criacao` DATETIME DEFAULT NULL,
  `dt_modificado` DATETIME DEFAULT NULL,
  `imagem` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id_cor`),
  FOREIGN KEY (`id_linha`) REFERENCES `linha` (`id_linha`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8
;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE cor;
TRUNCATE TABLE linha;
TRUNCATE TABLE marca;

INSERT INTO marca VALUES (0, 'Duratex', 1, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Singular', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Mineral Dourado', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Calacata', 1, 1, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Conceito', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Hong Kong', 1, 2, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Arenito', 1, 2, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Bolonha', 1, 2, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Eclipse', 1, 2, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Gobi', 1, 2, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Lana', 1, 2, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Luna4', 1, 2, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Tramato', 1, 2, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Cristallo', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Utramarino', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Croma', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Pau Ferro Natural', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Branco Diamante', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Cinza Sagrado', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Gianduia', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Maya', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Opala', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Preto', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Titânio', 1, 3, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Noturno', 1, 3, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Design', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Brise', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Maranata', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Avelã', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Berlin', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Hanover', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Malva', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Munique', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Ibiza', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Metrópole', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Nogueira Caiena', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Nogueira Thar', 1, 4, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Trancoso', 1, 4, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Essencial Wood', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Cumaru Raiz', 1, 5, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Luar', 1, 5, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Eterno', 1, 5, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Álamo', 1, 5, NOW(), NOW()); 
INSERT INTO cor VALUES (0, 'Itapuã', 1, 5, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Inhotim', 1, 5, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Rovere Marsala', 1, 5, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Batur', 1, 5, NOW(), NOW()); 
INSERT INTO cor VALUES (0, 'Freijó Puro', 1, 5, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Essencial', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Artesanal', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Azul Secreto', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Branco Diamante', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Calacata', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Cinza Sagrado', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Gepeto', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Mint', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Nocê Amêndoa', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Noce Califórnia', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Noce Mare', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Orion', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Pérola Urbana', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Portoro', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Prata', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Rosa Glamour', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Rovere Sereno', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Steel', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Verde Real', 1, 6, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Thassos', 1, 6, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Trama', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Aurora', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Deserto', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Positano', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Branco Ártico', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Branco Diamante', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Gianduia', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Grafite', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Preto', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Sirena', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Titânio', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carbono', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Stucco', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Nobile', 1, 7, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Yes', 1, 7, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Cross', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Arizona', 1, 8, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Riviera', 1, 8, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Prisma', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Rústico', 1, 9, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Larnaca', 1, 9, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Carvalho Évora', 1, 9, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Ébano Grigio', 1, 9, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Nogueira Cadiz', 1, 9, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Lineo Têxtil', 1, 9, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Duna', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Amêndola Rústica', 1, 10, NOW(), NOW());
INSERT INTO linha VALUES (0, 'Original', 1, 1, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Branco Ártico', 1, 11, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Cristal', 1, 11, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Ovo', 1, 11, NOW(), NOW());
INSERT INTO cor VALUES (0, 'Preto', 1, 11, NOW(), NOW());
SET FOREIGN_KEY_CHECKS = 1;