<?php

/*
    Called from 'index.php'.
    This function will use tb_tests and tb_test_words to find the words the user gets a high score on most often. 
    This file is using constants that are defined in 'constants.php'.

    CHANGE HISTORY:

    06-04-25: LIVE FIX. New random order on returned results.
*/
function find_potential_mastered_words() {

	include("include/connection.php");
    include_once("include/constants.php");


    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Set variables
    $max_score = HIGHSCORE;
    $occurence = 1;
    $mastered = NO;

    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    $sql = "SELECT tw.word, tw.vocab_id
            FROM tb_test_words tw
            JOIN tb_vocab v ON tw.vocab_id = v.id
            WHERE tw.score >= ?
            AND v.is_mastered = ?
            AND v.user_id=?
            GROUP BY tw.vocab_id, tw.word
            HAVING COUNT(tw.vocab_id) > ? ORDER BY RAND();";

   /* 
   CHANGING THIS SQL AS IT DOESN'T WORK WHEN ADDING IN CHECK FOR A MASTERED WORD
    $sql = "SELECT tb_tests.user_id, 
                    tb_tests.test_id, 
                    tb_tests.category_desc, 
                    tb_test_words.word, 
                    tb_test_words.score, 
                    tb_test_words.vocab_id
            FROM tb_tests
            INNER JOIN tb_test_words ON tb_tests.test_id = tb_test_words.test_id
            WHERE tb_tests.user_id = ? 
            AND tb_test_words.score = ?
            GROUP BY tb_test_words.vocab_id
            HAVING COUNT(tb_test_words.vocab_id) > ?";
    */
    //Prepare the statement
    $run_sql = $conn->prepare($sql);
    $run_sql->bind_param("isii", $max_score, $mastered, $user_id, $occurence );

    if(!$run_sql->execute()) {
        echo ("Error finding potential mastered words: ") . $run_sql->error;
    }

    $result = $run_sql->get_result();

    //initialise array
    $word_array = [];

    //if it finds at least 1 row, get the details.
    if ($result->num_rows > 0) {

        //loop through and add to array.
        while ($row = $result->fetch_assoc()) {
            $word_array[] = $row;
        }
    }

    //Close connections
    $run_sql->close();
    $conn->close();

    return $word_array;


}


/*
    Called from 'index.php'.
    This function will use tb_tests and tb_test_words to find the words the user gets a low score on most often. 
    The lowscore value is held in 'constants.php'.

    CHANGE HISTORY:

    06-04-25: LIVE FIX. New random order on returned results.
*/
function find_low_score_words() {

	include("include/connection.php");


    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Set variables
    $max_score = LOWSCORE;
    $occurence = 1;
    $mastered = NO;

    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    $sql_low_score = "SELECT tw.word, tw.vocab_id, tw.category_desc
                    FROM tb_test_words tw
                    JOIN tb_vocab v ON tw.vocab_id = v.id
                    WHERE tw.score <= ?
                    AND v.is_mastered = ?
                    AND v.user_id=?
                    GROUP BY tw.vocab_id, tw.word
                    HAVING COUNT(tw.vocab_id) > ? ORDER BY RAND() LIMIT 15;";

    //Prepare the statement
    $run_sql_low_score = $conn->prepare($sql_low_score);
    $run_sql_low_score->bind_param("isii", $max_score, $mastered, $user_id, $occurence );

    if(!$run_sql_low_score->execute()) {
        echo ("Error finding potential mastered words: ") . $run_sql_low_score->error;
    }

    $result_low_score = $run_sql_low_score->get_result();

    //initialise array
    $word_low_score_array = [];

    //if it finds at least 1 row, get the details.
    if ($result_low_score->num_rows > 0) {

        //loop through and add to array.
        while ($row = $result_low_score->fetch_assoc()) {
            $word_low_score_array[] = $row;
        }
    }

    //Close connections
    $run_sql_low_score->close();
    $conn->close();

    return $word_low_score_array;

}


/*
    Called from 'index.php'.
    This function will use tb_tests and tb_test_words to return the users average accuracy value. 
*/
function calc_average_word_accuracy() {
 
    include("include/connection.php");


    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare varaibles
    $accuracy = 0;

    $sql_avg = "SELECT t.user_id,
                AVG(w.score) AS average_accuracy
            FROM 
                tb_tests t
            JOIN 
                tb_test_words w 
            ON 
                t.test_id = w.test_id
            WHERE 
                t.user_id=?
            GROUP BY 
                t.user_id
            ORDER BY 
                average_accuracy DESC;";

    //Prepare the statement
    $run_avg = $conn->prepare($sql_avg);
    $run_avg->bind_param("i", $user_id, );

    if(!$run_avg->execute()) {
        echo ("Error finding average accuracy: ") . $run_avg->error;
    }

    $run_avg_result = $run_avg->get_result();
    $row = $run_avg_result->fetch_assoc();

    //Find number of rows.
    $num_rows = $run_avg_result->num_rows;

    if ($num_rows > 0) {
        $accuracy = $row['average_accuracy'];
    }

    //Close connections
    $run_avg->close();
    $conn->close();

    return intval($accuracy);
}

/*
    FUNCTION NOT IN USE (BUT WORKS)
    Replaced by calc_category_highest_and_lowest_accuracy()
*/
function calc_category_highest_accuracy() {
 
    include("include/connection.php");


    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare varaibles
    $category_accuracy_title = '';
    $category_accuracy = 0;

    $sql_avg = "SELECT 
                    w.category_desc,
                    AVG(w.score) AS average_score
                FROM 
                    tb_test_words w
                JOIN 
                    tb_tests t 
                ON 
                    w.test_id = t.test_id
                WHERE 
                    t.user_id =?  
                GROUP BY 
                    w.category_desc
                ORDER BY 
                    average_score DESC
                LIMIT 1;";

    //Prepare the statement
    $run_avg = $conn->prepare($sql_avg);
    $run_avg->bind_param("i", $user_id, );

    if(!$run_avg->execute()) {
        echo ("Error finding average accuracy: ") . $run_avg->error;
    }

    $run_avg_result = $run_avg->get_result();

    if ($row = $run_avg_result->fetch_assoc()) {
        $category_accuracy_title = $row['category_desc'];
        $category_accuracy = $row['average_score'];
    }


    //Close connections
    $run_avg->close();
    $conn->close();

    return [$category_accuracy_title, intval($category_accuracy)];
}


/*
    This function is called from the index.php page. 
    It will calculate what the users current days streak is in adding words. 
*/
function find_test_streak() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //SQL to calculate the streak.
    $sql_streak = "WITH ranked_entries AS ( 
                SELECT test_date, 
                RANK() OVER (ORDER BY test_date DESC) AS rnk 
                FROM `tb_tests` 
                WHERE user_id=? 
                GROUP BY test_date
                ), 
                streak_data AS ( 
                    SELECT test_date, 
                        DATEDIFF(CURDATE(), test_date) AS diff, 
                        rnk 
                        FROM ranked_entries 
                    ) 
                    SELECT COUNT(*) AS streak_days 
                    FROM streak_data 
                    WHERE diff = rnk - 1;";

    //Prepare the statements
    $run_streak_sql = $conn->prepare($sql_streak);
    
    if (!$run_streak_sql) {
        die("SQL error (current test streak): " . $conn->error);
    }

    //Bind parameter & execute
    $run_streak_sql->bind_param("i", $user_id);
    
    if (!$run_streak_sql->execute()) {
        die("SQL error (current test streak): " . $run_streak_sql->error);

    }

    //Get the result
    $result_streak = $run_streak_sql->get_result();
    $row = $result_streak->fetch_assoc();
    $test_streak = $row['streak_days'] ?? 0;

    //Close connections
    $run_streak_sql->close();
    $conn->close();

    return $test_streak;

}

/*
    Called from 'index.php'.
    This function will use 'tb_tests' to count how many tests a user has completed. 
*/
function number_of_user_tests() {
 
    include("include/connection.php");


    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare varaibles
    $tot_tests = 0;

    $sql_total = "SELECT COUNT(*) AS num_tests FROM `tb_tests` WHERE user_id = ?";

    //Prepare the statement
    $run_sql_total = $conn->prepare($sql_total);
    $run_sql_total->bind_param("i", $user_id, );

    if(!$run_sql_total->execute()) {
        echo ("Error finding total number of tests: ") . $run_sql_total->error;
    }

    $run_total_result = $run_sql_total->get_result();
    $row = $run_total_result->fetch_assoc();

    //Find number of rows.
    $num_rows = $run_total_result->num_rows;

    if ($num_rows > 0) {
        $tot_tests = $row['num_tests'];
    }

    //Close connections
    $run_sql_total->close();
    $conn->close();

    return $tot_tests;
}


/*
    Called from 'index.php'.
    The SQL in this function will group all the category average accuracy scores together. 
    It will then take both the highest and lowest value and pass back to 'index.php'
    This replaces 'calc_category_highest_accuracy' function.
*/
function calc_category_highest_and_lowest_accuracy() {
 
    include("include/connection.php");


    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare varaibles
    $category_accuracy_title = '';
    $category_accuracy = 0;

    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    $sql_avg = "SELECT 
            w.category_desc,
            AVG(w.score) AS average_score
        FROM 
            tb_test_words w
        JOIN 
            tb_tests t 
        ON 
            w.test_id = t.test_id
        WHERE 
            t.user_id = ?  
        GROUP BY 
            w.category_desc;";

    //Prepare the statement
    $run_avg = $conn->prepare($sql_avg);
    $run_avg->bind_param("i", $user_id, );

    if(!$run_avg->execute()) {
        echo ("Error finding average accuracy: ") . $run_avg->error;
    }

    $run_avg_result = $run_avg->get_result();

    $categories_avg = [];
    while ($row = $run_avg_result->fetch_assoc()) {
        $categories_avg[$row['category_desc']] = $row['average_score'];
    }

    $maxCategory = array_keys($categories_avg, max($categories_avg))[0];
    $minCategory = array_keys($categories_avg, min($categories_avg))[0];

    //Close connections
    $run_avg->close();
    $conn->close();


    return [
        'highest' => [
            'category' => $maxCategory,
            'score' => $categories_avg[$maxCategory]
        ],
        'lowest' => [
            'category' => $minCategory,
            'score' => $categories_avg[$minCategory]
        ]
    ];
}



/**
 *  This function will look at tb_streaks to find the test target they have set. 
 */
function find_users_streak_targets() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //php code to select from db
    $sql = "SELECT max_test_streak, max_word_streak FROM `tb_streaks` WHERE user_id='$user_id'";
    $run = mysqli_query($conn, $sql);

    //Check for errors on sql query
    if (!$run) {
        echo "Error accessing tb_streaks (tests): " . mysqli_error($conn);
    } 

    if ($run->num_rows > 0) {

        while ($row = $run->fetch_assoc()) {
            $max_test_streak = $row['max_test_streak'];
            $max_word_streak = $row['max_word_streak'];
        }
    } else {
        $max_test_streak = 0;
        $max_word_streak = 0;
    }

    return [$max_test_streak, $max_word_streak];
}