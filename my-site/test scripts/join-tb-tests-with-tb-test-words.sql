

-- General select on these two tables
SELECT tb_tests.user_id, tb_tests.test_id, tb_tests.category_desc, tb_test_words.word, tb_test_words.score, tb_test_words.vocab_id
FROM tb_tests
INNER JOIN tb_test_words ON tb_tests.test_id = tb_test_words.test_id;


-- Select with specific user
SELECT tb_tests.user_id, tb_tests.test_id, tb_tests.category_desc, tb_test_words.word, tb_test_words.score, tb_test_words.vocab_id
FROM tb_tests
INNER JOIN tb_test_words ON tb_tests.test_id = tb_test_words.test_id
WHERE tb_tests.user_id = '143436958';

-- SQL 
SELECT tb_tests.user_id, 
                    tb_tests.test_id, 
                    tb_tests.category_desc, 
                    tb_test_words.word, 
                    tb_test_words.score, 
                    tb_test_words.vocab_id
            FROM tb_tests
            INNER JOIN tb_test_words ON tb_tests.test_id = tb_test_words.test_id
            WHERE tb_tests.user_id = '1234' 
            AND tb_test_words.score = 100
            GROUP BY tb_test_words.vocab_id
            HAVING COUNT(tb_test_words.vocab_id) > 1


            SELECT tw.word
FROM tb_test_words tw
JOIN tb_vocab v ON tw.vocab_id = v.id
WHERE tw.score = 100
  AND v.is_mastered = 'N'
  AND v.user_id = '143436958'
GROUP BY tw.vocab_id, tw.word
HAVING COUNT(tw.vocab_id) > 1;