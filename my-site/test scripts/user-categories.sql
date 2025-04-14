-- User:        cat1
-- Password:    cat1
-- UserId:      25577730233377476

-- User:        cat2
-- Password:    cat2
-- UserId:      2802



-- SQL add categories to 'tb_user_categories' for existing users, so I can test adding / editing categories

-- User:        bob
-- Password:    pwbob
-- UserId:      143436958

INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',1,'ART');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',2,'BUSINESS & WORK');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',3,'EVERDAY');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',4,'FAMILY');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',5,'FOOD');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',6,'NATURE');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',7,'POLITICS');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',8,'TECHNOLOGY');
INSERT INTO `tb_user_categories`(`user_id`,`category_id`,`category_desc`) VALUES ('143436958',9,'TRAVEL');