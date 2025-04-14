-- There is a test to be done to ensure that the trigger on tb_user_categories is working.
-- The trigger will fire when a user deletes a category_desc row from tb_user_categories. It will update any row on tb_vocab
-- for that user_id where the category_desc is the same as the one being deleted. It will update the category_desc on tb_vocab
-- to read as 'DELETED'. 

/*

    DELIMITER $$

    CREATE TRIGGER update_tb_vocab_on_delete
    AFTER DELETE ON tb_user_categories
    FOR EACH ROW
    BEGIN
        UPDATE tb_vocab
        SET category_desc = 'DELTED'
        WHERE category_desc = OLD.category_desc
        AND user_id = OLD.user_id;
    END $$

    DELIMITER ;

*/

-- STEPS;
-- 1. Create a category for test user.
-- 2. Add words to this category. 
-- 3. Delete the category using the delete category function.
-- 4. Check the DB (tb_vocab) to see if the category_desc value for these words is now 'DELETED'.

-- Tested on 09-03-25, with bob. Working.