-- User:        plat1
-- Password:    plat1
-- UserId:      6084420857

-- User:        plat2
-- Password:    plat2
-- UserId:      17129577


-------------------------------------
-- 07-03-25: Seems to be working. 
--           Checked with second user and they are not being awarded the platinum.
-------------------------------------


-- Need to add some words - plat1

INSERT INTO `tb_vocab`(`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc`, `is_mastered`) 
VALUES ('Lundi','Monday','6084420857',0, 'Nature','N');

INSERT INTO `tb_vocab`(`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc`, `is_mastered`) 
VALUES ('Janvier','January','6084420857',0, 'Nature','N');

INSERT INTO `tb_vocab`(`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc`, `is_mastered`) 
VALUES ('Le pain','Bread','6084420857',0, 'Nature','N');

-- Need to add some words - plat2

INSERT INTO `tb_vocab`(`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc`, `is_mastered`) 
VALUES ('Le chat','Cat','17129577',0, 'Nature','N');

INSERT INTO `tb_vocab`(`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc`, `is_mastered`) 
VALUES ('Mars','March','17129577',0, 'Nature','N');

INSERT INTO `tb_vocab`(`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc`, `is_mastered`) 
VALUES ('Le pizza','Pizza','17129577',0, 'Nature','N');

-- Need 12 rows on tb_badge_record to start the platinum process (13th badge is for account creation, 14th is for platinum) - plat1

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',2,'User has added 1 word');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',3,'User has used all 9 categories');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',4,'User has taken first test.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',5,'User has input 25 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',6,'User has input 100 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',7,'User has input 250 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',8,'User has input 1000 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',9,'User has submitted a message via Contact form.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',10,'User has mastered 1 word in library.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',11,'User has mastered 25 words in library');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',12,'User has mastered 100 words in library');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('6084420857',13,'User has mastered 250 words in library');

-- Need 12 rows on tb_badge_record to start the platinum process (13th badge is for account creation, 14th is for platinum) - plat2

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',2,'User has added 1 word');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',3,'User has used all 9 categories');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',4,'User has taken first test.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',5,'User has input 25 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',6,'User has input 100 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',7,'User has input 250 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',8,'User has input 1000 words.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',9,'User has submitted a message via Contact form.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',10,'User has mastered 1 word in library.');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',11,'User has mastered 25 words in library');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',12,'User has mastered 100 words in library');

INSERT INTO `tb_badge_record`(`user_id`, `badge_num`, `badge_desc`) VALUES ('17129577',13,'User has mastered 250 words in library');