-- ---
-- Test Data
-- ---


-- Table 'category'

INSERT INTO `category` (`cat_id`,`cat_name`) VALUES
(1,'aérienne'),
(2,'bobine'),
(3,'commémoratif'),
(4,'définitif');

-- Table 'stamp'

INSERT INTO `stamp` (`st_id`,`st_cat_id`,`st_condition`,`st_width`,`st_height`,`st_start_price`,`st_end_price`,`st_active`,`st_date`,`st_title`,`st_description`,`st_image_principal`,`st_image_supplement`,`st_country`,`st_continent`,`st_certifie`,`st_tirage`,`st_au_id`) VALUES
(1,1,'excellente',40,20,5.00,  15.00, 1,'2023-07-01','title de timbre 1','description de timbre 1','airmail-green.webp',      null,'Irlande',   'Europe',1,1,1),
(2,1,'excellente',40,20,5.00,  15.00, 1,'2023-07-01','title de timbre 2','description de timbre 2','airmail-blue.webp',       null,'Canada',    'Amérique',1,1,1),
(3,1,'excellente',40,20,5.00,  15.00, 1,'2023-07-01','title de timbre 3','description de timbre 3','airmail-red.webp',        null,'États-Unis','Amérique',1,1,1),
(4,3,'excellente',20,40,6.00,  8.00,  1,'2023-07-01','title de timbre 4','description de timbre 4','commemorative-blue.webp', null,'États-Unis','Amérique',1,1,2),
(5,2,'excellente',40,20,23.00, 45.00, 1,'2023-07-01','title de timbre 5','description de timbre 5','coil-red.webp',           null,'Russie',    'Europe',1,2,2),
(6,4,'excellente',20,40,16.00, 38.00, 1,'2023-07-01','title de timbre 6','description de timbre 6','definitive-red.webp',     null,'Hongrois',  'Europe',1,1,3),
(7,3,'excellente',40,20,7.00,  9.00,  1,'2023-07-01','title de timbre 7','description de timbre 7','commemorative-white.webp',null,'Allemagne', 'Europe',1,1,4),
(8,2,'excellente',30,40,120.00,480.00,1,'2023-07-01','title de timbre 8','description de timbre 8','coil-green.webp',         null,'Chine',     'Asie',1,1,5);

-- Table 'user'

INSERT INTO `user` (`user_id`,`user_nom`,`user_prenom`,`user_email`,`user_mdp`,`user_active`,`user_role_id`) VALUES
(1,'Mcdonald','Jacques','jacques.mcdonald@site1.ca',SHA2("a1b2c3d4e5", 512), 1 ,1),
(2,'Stampee', 'Lord',   'lord.stampee@site2.ca',    SHA2("u1v2w3x4y5", 512), 1 ,2),
(3,'Tremblay','Olivier','olivier.tremblay@site3.ca',SHA2("f1g2h3i4j5", 512), 1 ,3),
(4,'Durand',  'Jean',   'jean.durand@site4.ca',     SHA2("k1l2m3n4o5", 512), 1 ,3),
(5,'Fontaine','Pierre', 'pierre.fontaine@site5.ca', SHA2("p1q2r3s4t5", 512), 1 ,3);

-- Table 'auction'

INSERT INTO `auction` (`au_id`,`au_user_id`,`au_prix_plancher`,`au_start_date`,`au_end_date`,`au_lord`) VALUES
(1,3,3.00,'2023-07-01 09:30:00','2023-07-02 22:30:00',1),
(2,4,4.00,'2023-07-03 18:30:00','2023-07-03 24:00:00',1),
(3,5,5.00,'2023-07-04 11:00:00','2023-07-05 22:00:00',1),
(4,3,6.00,'2023-07-06 09:30:00','2023-07-06 22:30:00',0),
(5,3,9.00,'2023-07-07 09:30:00','2023-07-08 22:30:00',1);

-- Table 'user_role'

INSERT INTO `user_role` (`role_id`,`role`) VALUES
(1,'administrateur'),
(2,'propriétaire'),
(3,'membre');

-- Table 'offre'

INSERT INTO `offre` (`offre_id`,`offre_au_id`,`offre_user_id`,`offre_price`,`offre_date`,`offre_success`) VALUES
(null,1,5,5.00,'2023-07-01 11:00:00',0),
(null,1,4,10.00,'2023-07-01 13:00:00',0),
(null,1,5,15.00,'2023-07-02 11:00:00',1),
(null,2,3,5.00,'2023-07-03 20:00:00',1);

-- Table 'stamp_color'

INSERT INTO `stamp_color` (`sc_id`,`sc_st_id`,`sc_color_id`) VALUES
(null,1,2),
(null,2,1),
(null,3,3),
(null,4,1),
(null,5,3),
(null,6,3),
(null,7,3),
(null,8,2);

-- Table 'color'


INSERT INTO `color` (`color_id`,`color`) VALUES
(1,'bleu'),
(2,'vert'),
(3,'rouge'),
(4,'noir');