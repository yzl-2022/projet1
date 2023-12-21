-- ---
-- Globals
-- ---

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;

--
-- Database: `stampee`
--

DROP DATABASE IF EXISTS `stampee`;

CREATE DATABASE IF NOT EXISTS `stampee` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `stampee`;

-- ---
-- Table 'stamp'
-- 
-- ---

DROP TABLE IF EXISTS `stamp`;
		
CREATE TABLE `stamp` (
  `st_id` INT NOT NULL AUTO_INCREMENT,
  `st_au_id` INT NOT NULL,
  `st_condition` ENUM('parfaite','excellente','bonne','moyenne','endommage') NOT NULL,
  `st_width` TINYINT NOT NULL,
  `st_height` TINYINT NOT NULL,
  `st_title` VARCHAR(100) NOT NULL,
  `st_description` VARCHAR(100) NULL DEFAULT NULL,
  `st_country` VARCHAR(100) NOT NULL,
  `st_continent` ENUM('Afrique','Amérique','Antarctique','Asie','Europe','Océanie') NOT NULL,
  `st_certifie` TINYINT(1) NOT NULL DEFAULT 0,
  `st_tirage` SMALLINT NOT NULL,
  `st_color` ENUM('bleu','vert','rouge','noir','violet','brun', 'jaune') NOT NULL,
  `st_cat_id` INT NOT NULL,
  PRIMARY KEY (`st_id`)
);

-- ---
-- Table 'photo'
-- 
-- ---

DROP TABLE IF EXISTS `photo`;
		
CREATE TABLE `photo` (
  `photo_id` INT NOT NULL AUTO_INCREMENT,
  `photo_st_id` INT NOT NULL,
  `photo_name` VARCHAR(100) NOT NULL,
  `photo_principal` TINYINT(1) NOT NULL,
  PRIMARY KEY (`photo_id`)
);

-- ---
-- Table 'auction'
-- 
-- ---

DROP TABLE IF EXISTS `auction`;
		
CREATE TABLE `auction` (
  `au_id` INT NOT NULL AUTO_INCREMENT,
  `au_user_id` INT NOT NULL,
  `au_prix_plancher` DOUBLE NOT NULL,
  `au_start_date` DATETIME NOT NULL,
  `au_end_date` DATETIME NOT NULL,
  `au_lord` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`au_id`)
);

-- ---
-- Table 'user'
-- 
-- ---

DROP TABLE IF EXISTS `user`;
		
CREATE TABLE `user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `user_nom` VARCHAR(100) NOT NULL,
  `user_prenom` VARCHAR(100) NOT NULL,
  `user_email` VARCHAR(100) NOT NULL,
  `user_mdp` VARCHAR(100) NOT NULL,
  `user_role_id` INT NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY (`user_email`)
);

-- ---
-- Table 'user_role'
-- 
-- ---

DROP TABLE IF EXISTS `user_role`;
		
CREATE TABLE `user_role` (
  `role_id` INT NOT NULL AUTO_INCREMENT,
  `role` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY (`role`)
);

-- ---
-- Table 'offre'
-- 
-- ---

DROP TABLE IF EXISTS `offre`;
		
CREATE TABLE `offre` (
  `offre_id` INT NOT NULL AUTO_INCREMENT,
  `offre_au_id` INT NOT NULL,
  `offre_user_id` INT NOT NULL,
  `offre_price` DOUBLE NOT NULL,
  `offre_date` DATETIME NOT NULL,
  `offre_success` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`offre_id`)
);

-- ---
-- Table 'favoris'
-- 
-- ---

DROP TABLE IF EXISTS `favoris`;
		
CREATE TABLE `favoris` (
  `fav_id` INT NOT NULL AUTO_INCREMENT,
  `fav_au_id` INT NOT NULL,
  `fav_user_id` INT NOT NULL,
  PRIMARY KEY (`fav_id`),
  UNIQUE KEY (`fav_au_id`, `fav_user_id`)
);

-- ---
-- Table 'category'
-- 
-- ---

DROP TABLE IF EXISTS `category`;
		
CREATE TABLE `category` (
  `cat_id` INT NOT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY (`cat_name`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `stamp` ADD FOREIGN KEY (st_au_id) REFERENCES `auction` (`au_id`);
ALTER TABLE `stamp` ADD FOREIGN KEY (st_cat_id) REFERENCES `category` (`cat_id`);
ALTER TABLE `photo` ADD FOREIGN KEY (photo_st_id) REFERENCES `stamp` (`st_id`);
ALTER TABLE `auction` ADD FOREIGN KEY (au_user_id) REFERENCES `user` (`user_id`);
ALTER TABLE `user` ADD FOREIGN KEY (user_role_id) REFERENCES `user_role` (`role_id`);
ALTER TABLE `offre` ADD FOREIGN KEY (offre_au_id) REFERENCES `auction` (`au_id`);
ALTER TABLE `offre` ADD FOREIGN KEY (offre_user_id) REFERENCES `user` (`user_id`);
ALTER TABLE `favoris` ADD FOREIGN KEY (fav_au_id) REFERENCES `auction` (`au_id`);
ALTER TABLE `favoris` ADD FOREIGN KEY (fav_user_id) REFERENCES `user` (`user_id`);

-- ---
-- Table Properties
-- ---

ALTER TABLE `stamp` ENGINE=InnoDB;
ALTER TABLE `photo` ENGINE=InnoDB;
ALTER TABLE `auction` ENGINE=InnoDB;
ALTER TABLE `user` ENGINE=InnoDB;
ALTER TABLE `user_role` ENGINE=InnoDB;
ALTER TABLE `offre` ENGINE=InnoDB;
ALTER TABLE `favoris` ENGINE=InnoDB;
ALTER TABLE `category` ENGINE=InnoDB;

-- ---
-- Test Data
-- ---

-- INSERT INTO `stamp` (`st_id`,`st_au_id`,`st_condition`,`st_width`,`st_end_price`,`st_title`,`st_description`,`st_country`,`st_continent`,`st_certifie`,`st_tirage`,`st_color`,`st_cat_id`) VALUES
-- ('','','','','','','','','','','','','','','');
-- INSERT INTO `photo` (`photo_id`,`photo_st_id`,`photo_name`,`photo_principal`) VALUES
-- ('','','','');
-- INSERT INTO `auction` (`au_id`,`au_user_id`,`au_prix_plancher`,`au_start_date`,`au_end_date`,`au_lord`) VALUES
-- ('','','','','','');
-- INSERT INTO `user` (`user_id`,`user_nom`,`user_prenom`,`user_email`,`user_mdp`,`user_role_id`) VALUES
-- ('','','','','','');
-- INSERT INTO `user_role` (`role_id`,`role`) VALUES
-- ('','');
-- INSERT INTO `offre` (`offre_id`,`offre_au_id`,`offre_user_id`,`offre_price`,`offre_date`,`offre_success`) VALUES
-- ('','','','','','');
-- INSERT INTO `favoris` (`fav_id`,`fav_au_id`,`fav_user_id`) VALUES
-- ('','','');
-- INSERT INTO `category` (`cat_id`,`cat_name`) VALUES
-- ('','');