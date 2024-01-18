-- ---
-- Test Data
-- ---

-- Table 'stamp'

INSERT INTO `stamp` (`st_id`,`st_au_id`,`st_condition`,`st_width`,`st_height`,`st_title`,`st_description`,`st_country`,`st_continent`,`st_certifie`,`st_tirage`,`st_color`,`st_cat_id`) VALUES
(1,1,'excellente',40,20,'title de timbre 1','description de timbre 1','Irlande',   'Europe',  1,1,'vert', 1),
(2,1,'excellente',40,20,'title de timbre 2','description de timbre 2','Canada',    'Amérique',1,1,'bleu', 1),
(3,1,'excellente',40,20,'title de timbre 3','description de timbre 3','États-Unis','Amérique',1,1,'rouge',1),
(4,2,'excellente',20,40,'title de timbre 4','description de timbre 4','États-Unis','Amérique',1,1,'bleu', 3),
(5,2,'excellente',40,20,'title de timbre 5','description de timbre 5','Russie',    'Europe',  1,2,'rouge',2),
(6,3,'excellente',20,40,'title de timbre 6','description de timbre 6','Hongrois',  'Europe',  1,1,'rouge',4),
(7,4,'excellente',40,20,'title de timbre 7','description de timbre 7','Allemagne', 'Europe',  1,1,'noir', 3),
(8,5,'excellente',30,40,'title de timbre 8','description de timbre 8','Chine',     'Asie',    1,1,'vert', 2);

-- Table 'photo'

INSERT INTO `photo` (`photo_id`,`photo_st_id`,`photo_name`,`photo_principal`) VALUES
(1,1,'airmail-green.webp',1),
(2,2,'airmail-blue.webp',1),
(3,3,'airmail-red.webp',1),
(4,4,'commemorative-blue.webp',1),
(5,5,'coil-red.webp',1),
(6,6,'definitive-red.webp',1),
(7,7,'commemorative-white.webp',1),
(8,8,'coil-green.webp',1);

-- Table 'auction'

INSERT INTO `auction` (`au_id`,`au_user_id`,`au_prix_plancher`,`au_start_date`,`au_end_date`,`au_lord`) VALUES
(1,3,3.00,'2024-01-11 09:30:00','2024-01-12 22:30:00',1),
(2,4,4.00,'2024-01-13 18:30:00','2024-01-13 23:00:00',1),
(3,5,5.00,'2024-01-14 11:00:00','2024-01-15 22:00:00',1),
(4,3,6.00,'2024-01-16 09:30:00','2024-01-16 22:30:00',0),
(5,3,9.00,'2024-01-17 09:30:00','2024-01-18 22:30:00',1);

-- Table 'user'

INSERT INTO `user` (`user_id`,`user_nom`,`user_prenom`,`user_email`,`user_mdp`,`user_role_id`) VALUES
(1,'Mcdonald','Jacques','jacques.mcdonald@site1.ca',"a1b2c3d4e5",1),
(2,'Stampee', 'Lord',   'lord.stampee@site2.ca',    "u1v2w3x4y5",2),
(3,'Tremblay','Olivier','olivier.tremblay@site3.ca',"f1g2h3i4j5",3),
(4,'Durand',  'Jean',   'jean.durand@site4.ca',     "k1l2m3n4o5",3),
(5,'Fontaine','Pierre', 'pierre.fontaine@site5.ca', "p1q2r3s4t5",3);


-- Table 'user_role'

INSERT INTO `user_role` (`role_id`,`role`) VALUES
(1,'administrateur'),
(2,'propriétaire'),
(3,'membre');

-- Table 'offre'

INSERT INTO `offre` (`offre_id`,`offre_au_id`,`offre_user_id`,`offre_price`,`offre_date`,`offre_success`) VALUES
(null,1,5,5.00,'2024-01-11 11:00:00',0),
(null,1,4,10.00,'2024-01-11 13:00:00',0),
(null,1,5,15.00,'2024-01-12 11:00:00',1),
(null,2,3,5.00,'2024-01-13 20:00:00',1);

-- Table 'favoris'

INSERT INTO `favoris` (`fav_id`,`fav_au_id`,`fav_user_id`) VALUES
(null,1,1),
(null,3,3);

-- Table 'category'

INSERT INTO `category` (`cat_id`,`cat_name`) VALUES
(1,'aérienne'),
(2,'bobine'),
(3,'commémoratif'),
(4,'définitif');