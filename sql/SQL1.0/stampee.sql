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
-- Table 'stamp'
-- 
-- ---

DROP TABLE IF EXISTS `stamp`;
		
CREATE TABLE `stamp` (
  `st_id` INT NOT NULL AUTO_INCREMENT,
  `st_cat_id` INT NULL DEFAULT NULL,
  `st_condition` ENUM('parfaite','excellente','bonne','moyenne','endommage') NOT NULL,
  `st_width` TINYINT NOT NULL DEFAULT 0,
  `st_height` TINYINT NOT NULL DEFAULT 0,
  `st_start_price` DOUBLE NOT NULL DEFAULT 0,
  `st_end_price` DOUBLE NOT NULL DEFAULT 0,
  `st_active` TINYINT(1) NOT NULL DEFAULT 1,
  `st_date` DATE NULL DEFAULT NULL,
  `st_title` VARCHAR(200) NULL DEFAULT NULL,
  `st_description` VARCHAR(200) NULL DEFAULT NULL,
  `st_image_principal` VARCHAR(200) NULL DEFAULT NULL,
  `st_image_supplement` VARCHAR(200) NULL DEFAULT NULL,
  `st_country` VARCHAR(100) NULL DEFAULT NULL,
  `st_continent` ENUM('Afrique','Amérique','Antarctique','Asie','Europe','Océanie') NULL DEFAULT NULL,
  `st_certifie` TINYINT(1) NOT NULL DEFAULT 1,
  `st_tirage` SMALLINT NULL DEFAULT NULL,
  `st_au_id` INT NOT NULL,
  PRIMARY KEY (`st_id`)
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
  `user_active` TINYINT(1) NOT NULL DEFAULT 1,
  `user_role_id` INT NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY (`user_email`)
);

-- ---
-- Table 'auction'
-- 
-- ---

DROP TABLE IF EXISTS `auction`;
		
CREATE TABLE `auction` (
  `au_id` INT NOT NULL AUTO_INCREMENT,
  `au_user_id` INT NOT NULL,
  `au_prix_plancher` DOUBLE NOT NULL DEFAULT 0,
  `au_start_date` DATETIME NOT NULL,
  `au_end_date` DATETIME NOT NULL,
  `au_lord` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`au_id`)
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
-- Table 'stamp_color'
-- 
-- ---

DROP TABLE IF EXISTS `stamp_color`;
		
CREATE TABLE `stamp_color` (
  `sc_id` INT NOT NULL AUTO_INCREMENT,
  `sc_st_id` INT NOT NULL,
  `sc_color_id` INT NOT NULL,
  PRIMARY KEY (`sc_id`),
  UNIQUE KEY (`sc_st_id`, `sc_color_id`)
);

-- ---
-- Table 'color'
-- 
-- ---

DROP TABLE IF EXISTS `color`;
		
CREATE TABLE `color` (
  `color_id` INT NOT NULL AUTO_INCREMENT,
  `color` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`color_id`),
  UNIQUE KEY (`color`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `stamp` ADD FOREIGN KEY (st_cat_id) REFERENCES `category` (`cat_id`);
ALTER TABLE `stamp` ADD FOREIGN KEY (st_au_id) REFERENCES `auction` (`au_id`);
ALTER TABLE `user` ADD FOREIGN KEY (user_role_id) REFERENCES `user_role` (`role_id`);
ALTER TABLE `auction` ADD FOREIGN KEY (au_user_id) REFERENCES `user` (`user_id`);
ALTER TABLE `offre` ADD FOREIGN KEY (offre_au_id) REFERENCES `auction` (`au_id`);
ALTER TABLE `offre` ADD FOREIGN KEY (offre_user_id) REFERENCES `user` (`user_id`);
ALTER TABLE `stamp_color` ADD FOREIGN KEY (sc_st_id) REFERENCES `stamp` (`st_id`);
ALTER TABLE `stamp_color` ADD FOREIGN KEY (sc_color_id) REFERENCES `color` (`color_id`);

-- ---
-- Table Properties
-- ---

ALTER TABLE `category` ENGINE=InnoDB;
ALTER TABLE `stamp` ENGINE=InnoDB;
ALTER TABLE `user` ENGINE=InnoDB;
ALTER TABLE `auction` ENGINE=InnoDB;
ALTER TABLE `user_role` ENGINE=InnoDB;
ALTER TABLE `offre` ENGINE=InnoDB;
ALTER TABLE `stamp_color` ENGINE=InnoDB;
ALTER TABLE `color` ENGINE=InnoDB;

-- ---
-- Test Data
-- ---

-- INSERT INTO `category` (`cat_id`,`cat_name`) VALUES
-- ('','');
-- INSERT INTO `stamp` (`st_id`,`st_cat_id`,`st_condition`,`st_width`,`st_height`,`st_start_price`,`st_end_price`,`st_active`,`st_date`,`st_title`,`st_description`,`st_image_principal`,`st_image_supplement`,`st_country`,`st_continent`,`st_certifie`,`st_tirage`,`st_au_id`) VALUES
-- ('','','','','','','','','','','','','','','','','','');
-- INSERT INTO `user` (`user_id`,`user_nom`,`user_prenom`,`user_email`,`user_mdp`,`user_active`,`user_role_id`) VALUES
-- ('','','','','','','');
-- INSERT INTO `auction` (`au_id`,`au_user_id`,`au_prix_plancher`,`au_start_date`,`au_end_date`,`au_lord`) VALUES
-- ('','','','','','');
-- INSERT INTO `user_role` (`role_id`,`role`) VALUES
-- ('','');
-- INSERT INTO `offre` (`offre_id`,`offre_au_id`,`offre_user_id`,`offre_price`,`offre_date`,`offre_success`) VALUES
-- ('','','','','','');
-- INSERT INTO `stamp_color` (`sc_id`,`sc_st_id`,`sc_color_id`) VALUES
-- ('','','');
-- INSERT INTO `color` (`color_id`,`color`) VALUES
-- ('','');