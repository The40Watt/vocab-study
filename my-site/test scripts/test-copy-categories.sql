-- User:        copy1
-- Password:    copy1
-- UserId:      56939638


-- SQL to fill out original categories in tb_categories

INSERT INTO `tb_categories`(`category_desc`) VALUES ('ART');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('BUSINESS & WORK');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('EVERDAY');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('FAMILY');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('FOOD');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('NATURE');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('POLITICS');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('TECHNOLOGY');
INSERT INTO `tb_categories`(`category_desc`) VALUES ('TRAVEL');

-- SQL to select from tb_user_categories

SELECT * FROM `tb_user_categories` where user_id='56939638';