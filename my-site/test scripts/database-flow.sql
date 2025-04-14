-- Need to create a new user to map out the flow of functions and associated DB updates.

-- User:        flow1
-- Password:    flow1
-- UserId:      1209106850986448


-- User creation results in;
-- 1 row added to tb_users
-- 9 rows added to tb_user_categories, there are 9 default categories.
-- 1 row added to tb_streaks

SELECT * FROM `tb_users` WHERE user_id='1209106850986448';

SELECT * FROM `tb_user_categories` WHERE user_id='1209106850986448';

SELECT * FROM `tb_streaks` WHERE user_id='1209106850986448';


--##############################################################
-- ADDING 1 WORD TO DB (l'art, art).
-- 1 row added to 'tb_vocab', test_count of word is 0. Date mastered is same date as date (date word added).
-- 1 row added to 'tb_badges'
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 

SELECT * FROM `tb_badge_record` WHERE user_id='1209106850986448';


--##############################################################
-- ADDING 5 MORE WORDS TO DB (piazz, bread, apples, strawberries, chocolateb)
-- 5 rows added to 'tb_vocab', test count is al 0.
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 

--##############################################################
-- COMPLETED FIRST TEST (not seeing update on tb_badges)
-- 1 row added to tb_tests (test_id = 52)
SELECT * FROM `tb_tests` WHERE user_id='1209106850986448';

-- 5 rows added to 'tb_test_words'
SELECT * FROM `tb_test_words` where test_id = 52;

-- 5 rows updated on 'tb_vocab' to update test_count of the 5 words tested
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 

-- 1 row added to 'tb_test_record', only after going back to index.php
SELECT * FROM `tb_badge_record` WHERE user_id='1209106850986448';

--##############################################################
-- Updating the users Target Language to French
-- 1 row updated on 'tb_users', the column 'tl_code' is now 'fr'
SELECT * FROM `tb_users` WHERE user_id='1209106850986448';


--##############################################################
-- Adding 3 new categories (Film, Music, Games)
-- User now has 12 rows on tb_user_categories

SELECT * FROM `tb_user_categories` WHERE user_id='1209106850986448';

--##############################################################
-- Delete Film category
-- User now has 11 rows on tb_user_categories
SELECT * FROM `tb_user_categories` WHERE user_id='1209106850986448';

--##############################################################
-- Add a word to the Music category
-- 1 row added to 'tb_vocab' with the cateogry Music
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 

--##############################################################
-- Delete the Music Category
-- 1 row deleted from tb_user_categories
SELECT * FROM `tb_user_categories` WHERE user_id='1209106850986448';

-- 1 row updated on tb_vocab, category is now 'DELETED'
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 


--##############################################################
-- Check Orphaned words, assign the orphaned word to the Games category
-- 1 row updated on 'tb_vocab', the word has been updated to the Games category
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 

--##############################################################
-- Need to edit the Category name of Games -> Video Games
-- 1 row updated on 'tb_vocab', the word has been updated to the Video Games category
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 

-- 1 row updated on the 'tb_user_categories' table, changing category_desc
SELECT * FROM `tb_user_categories` WHERE user_id='1209106850986448';

--##############################################################
-- Mark a word as 'Mastered' 
-- 1 row updated on 'tb_vocab', is_mastered set to Y, date_mastered updated to timestamp
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 

-- 1 row added to 'tb_badge_record'
SELECT * FROM `tb_badge_record` WHERE user_id='1209106850986448';

--##############################################################
-- Edit a word () 
-- 1 row updated on 'tb_vocab', change to reflect edit, date_mastered also updated to timestamp
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 


--##############################################################
-- Delete a word  
-- 1 row deleted from 'tb_vocab'
SELECT * FROM `tb_vocab` WHERE user_id='1209106850986448'; 


--##############################################################
-- Leave a message using the Contact form
--1 row has been added to 'tb_message'

SELECT * FROM `tb_message` WHERE user_id='1209106850986448';

--1 row has been added to 'tb_badge_record'
SELECT * FROM `tb_badge_record` WHERE user_id='1209106850986448';


