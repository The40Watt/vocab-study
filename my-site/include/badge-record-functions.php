<?php

/*
    This function will take in a parameter from 'index.php'. It will be one of several defined milestone numbers for words added.
    Depending on the number it will assign different values to the variables to be parsed into the SQL.
    It will first count rows on tb_badge_record to see if a row already exisits for that badge. If it does, nothing happens. 
    It there is no row, one will be inserted for the particular badge. 
    There is no value returned by this function. 
*/
function update_word_badges_records($milestone)
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //Declare variables.
    $badge_number = 0;
    $badge_desc = '';
    
    //Set up variables depending on word count.
    switch ($milestone) {
        case 1:
            $badge_number = 2;
            $badge_desc = 'User has added 1 word to library.';
            //echo ("this is 1");
            break;
        case 25:
            $badge_number = 5;
            $badge_desc = 'User has added 25 words to library.';
            echo ("this is 25");
            break;
        case 100:
            $badge_number = 6;
            $badge_desc = 'User has added 100 words to library';
            echo ("this is 100");
            break;
        case 250:
            $badge_number = 7;
            $badge_desc = 'User has added 250 words to library';
            echo ("this is 250");
            break;
        case 1000:
            $badge_number = 8;
            $badge_desc = 'User has added 1000 words to library';
            echo ("this is 1000");
            break;
    }


    //Count how many rows user has on tb_badge_record for the current badge number.
    $sql_check_row = "SELECT COUNT(*) AS count FROM `tb_badge_record` WHERE user_id = ? AND badge_num = ?";
    $run_check_row = $conn->prepare($sql_check_row);

    $run_check_row->bind_param("ii", $user_id, $badge_number);
    $run_check_row->execute();

    $run_check_row_result = $run_check_row->get_result();
    $row = $run_check_row_result->fetch_assoc();

    //If count is 0, no row on table.
    if ($row['count'] == 0) {

        $insert_sql = "INSERT INTO `tb_badge_record` (`user_id`, `badge_num`, `badge_desc`) VALUES (?, ?, ?)";
        $run_insert_sql = $conn->prepare($insert_sql);

        $run_insert_sql->bind_param("iis", $user_id, $badge_number, $badge_desc );

        if ($run_insert_sql->execute()) {
            //echo ("Row inserted to tb_badge_record.");
        } else {
            echo "Error inserting row (Badge #" . $badge_number . ": " . $run_insert_sql->error;
        }

        $run_insert_sql->close();
    } else {
        echo ("Row already exists on tb_badge_record.");   
    }

}


/*
    This function will do a count on tb_badges_record for a user. 
    The count will have 1 added to it automatically because a user will earn first badge by creating an account
    and this badge will not be on tb_badge_record.
    Calculates percentage and returns value.
*/

function calc_overall_badge_completion() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare variable
    $badges_complete = 0;
    $tot_available_badges = 14;

    //Check how many rows on table for current user..
    $sql_check_row = "SELECT COUNT(*) AS count FROM `tb_badge_record` WHERE user_id = ?";
    $run_check_row = $conn->prepare($sql_check_row);

    $run_check_row->bind_param("i", $user_id);
    $run_check_row->execute();

    $run_check_row_result = $run_check_row->get_result();
    $row = $run_check_row_result->fetch_assoc();

    $badges_complete = $row['count'];

    $badges_complete++; //Add 1 to count because user must have earned the 1st badge when creating account.

    $badges_complete = (($badges_complete / $tot_available_badges) * 100 );

    return intval($badges_complete);
}



/*

*/
function update_mastery_badge_records($mastered_count)
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //Declare variables.
    $badge_number = 0;
    $badge_desc = '';
    
    //Set up variables depending on mastered word count.
    switch ($mastered_count) {
        case 1:
            $badge_number = 10;
            $badge_desc = 'User has mastered 1 word in library.';
            echo ("this is 10");
            break;
        case 25:
            $badge_number = 11;
            $badge_desc = 'User has mastered 25 words in library.';
            echo ("this is 25");
            break;
        case 100:
            $badge_number = 12;
            $badge_desc = 'User has mastered 100 words in library.';
            echo ("this is 100");
            break;
        case 250:
            $badge_number = 13;
            $badge_desc = 'User has mastered 250 words in library.';
            echo ("this is 250");
            break;
    }


    //Count how many rows user has on tb_badge_record for the current badge number.
    $sql_check_row = "SELECT COUNT(*) AS count FROM `tb_badge_record` WHERE user_id = ? AND badge_num = ?";
    $run_check_row = $conn->prepare($sql_check_row);

    $run_check_row->bind_param("ii", $user_id, $badge_number);
    $run_check_row->execute();

    $run_check_row_result = $run_check_row->get_result();
    $row = $run_check_row_result->fetch_assoc();

    //If count is 0, no row on table.
    if ($row['count'] == 0) {

        $insert_sql = "INSERT INTO `tb_badge_record` (`user_id`, `badge_num`, `badge_desc`) VALUES (?, ?, ?)";
        $run_insert_sql = $conn->prepare($insert_sql);

        $run_insert_sql->bind_param("iis", $user_id, $badge_number, $badge_desc );

        if ($run_insert_sql->execute()) {
            //echo ("Row inserted to tb_badge_record.");
        } else {
            echo "Error inserting row (Badge #" . $badge_number . ": " . $run_insert_sql->error;
        }

        $run_insert_sql->close();
    } else {
        echo ("Row already exists on tb_badge_record.");   
    }

}


/*
    Function is passed in which badge we are concered about - does a select on tb_badge_record based on that. 
    If a row is found, gets the 'date_awarded'. 
    It will return 'Y' if row is found and the date.
*/
function check_badge_record($mastered_badge_num) {
	
    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $mastered_word = '';
    $mastered_date;
    

    //Select row from tb_badge_record
    $sql_check_row = "SELECT * FROM `tb_badge_record` WHERE user_id = ? AND badge_num = ?";

    $run_check_row = $conn->prepare($sql_check_row);

    $run_check_row->bind_param("ii", $user_id, $mastered_badge_num);
    $run_check_row->execute();

    $run_check_row_result = $run_check_row->get_result();
    $row = $run_check_row_result->fetch_assoc();

    $num_rows = $run_check_row_result->num_rows;

    //Declare variable
    $mastered_date = null;

    //If count is 0, no row on table.
    if ($num_rows == 1) {

        //row found so must have earned badge.
        $mastered_word = 'Y';
        $mastered_date = $row['date_awarded'];

    } else {
        $mastered_word = 'N';

    }

    return [$mastered_word, $mastered_date];

}



/*
    1. Will do a count on tb_vocab to see if user has used all 9 original categories.
    2. If they have, check that this achievement is recorded on tb_badge_record (#3)
    3. If not, add a row. 
*/
function count_categories_for_badge($badge_number) {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];
    $num_categories = 0;
    $badge_desc = 'User has used all 9 original categories.';

    //Prepare SQL and execute
    $sql = "SELECT COUNT(DISTINCT category_desc) AS unique_count FROM tb_vocab WHERE user_id='$user_id'";
    $run = mysqli_query($conn, $sql);

    //If query is successful, check for value. 
    if($run && $row = $run->fetch_assoc()) {

        //assign value to variable to return
        $num_categories = $row['unique_count'];

    } else {
        $num_categories = 0;
    }

    //Check if number of categories is 9, if so user has used all original categories. So next, check if this has been added to tb_badge_record table
    //and if not, add it.

    if ($num_categories == 9) {

        //Count how many rows user has on tb_badge_record for the current badge number.
        $sql_check_row = "SELECT COUNT(*) AS count FROM `tb_badge_record` WHERE user_id = ? AND badge_num = ?";
        $run_check_row = $conn->prepare($sql_check_row);

        $run_check_row->bind_param("ii", $user_id, $badge_number);
        $run_check_row->execute();

        $run_check_row_result = $run_check_row->get_result();
        $row = $run_check_row_result->fetch_assoc();

        //If count is 0, no row on table.
        if ($row['count'] == 0) {

            $insert_sql = "INSERT INTO `tb_badge_record` (`user_id`, `badge_num`, `badge_desc`) VALUES (?, ?, ?)";
            $run_insert_sql = $conn->prepare($insert_sql);

            $run_insert_sql->bind_param("iis", $user_id, $badge_number, $badge_desc );

            if ($run_insert_sql->execute()) {
                echo ("Row inserted to tb_bagdge_record.");
            } else {
                echo "Error inserting row for all 9 categories: " . $run_insert_sql->error;
            }

            $run_insert_sql->close();
        } else {
            //echo ("Row already exists on tb_badge_record.");   
        }
    }

}



/*
    Function to check if user has a row on tb_message.
    Takes in two parameters and will pass back if there is a row on the table
    and the date.
*/
function did_reach_out($badge_number) 
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $badge_desc = 'User has submitted a message via the Contact form.';
    $message_date = null;
    
    //php code to select from db
    $sql = "SELECT * FROM `tb_message` WHERE id_user='$user_id' ORDER BY date ASC LIMIT 1";
    $run = mysqli_query($conn, $sql);

    $has_contacted = mysqli_num_rows($run);

    //Find the date in the array
    while($row = mysqli_fetch_array($run)){
        $message_date = $row['date'];
    }

    if ($has_contacted > 0) {

        //Count how many rows user has on tb_badge_record for the current badge number.
        $sql_check_row = "SELECT COUNT(*) AS count FROM `tb_badge_record` WHERE user_id = ? AND badge_num = ?";
        $run_check_row = $conn->prepare($sql_check_row);

        $run_check_row->bind_param("ii", $user_id, $badge_number);
        $run_check_row->execute();

        $run_check_row_result = $run_check_row->get_result();
        $row = $run_check_row_result->fetch_assoc();

        //If count is 0, no row on table.
        if ($row['count'] == 0) {

            $insert_sql = "INSERT INTO `tb_badge_record` (`user_id`, `badge_num`, `badge_desc`) VALUES (?, ?, ?)";
            $run_insert_sql = $conn->prepare($insert_sql);

            $run_insert_sql->bind_param("iis", $user_id, $badge_number, $badge_desc );

            if ($run_insert_sql->execute()) {
                //echo ("Row inserted to tb_badge_record. (BADGE #9)");
            } else {
                echo "Error inserting row (Badge #9): " . $run_insert_sql->error;
            }

            $run_insert_sql->close();
        } else {
            //echo ("Row already exists on tb_badge_record (BADGE #9).");   
        }
    }
 

    return [$has_contacted, $message_date];

}


/*
    Function to check if the user has started a test. 
    Check tb_vocab for user where test_count is greater than 0.
*/
function check_user_has_tested($badge_number) {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];
    $badge_desc = 'User has taken first test.';
    $date_first_test = null;

    //Prepare SQL and execute
    $sql = "SELECT * FROM tb_vocab WHERE user_id='$user_id' AND test_count > 0";
    $run = mysqli_query($conn, $sql);

    //Number of rows found with test_count greater than 0 on tb_vocab
    $test_count = mysqli_num_rows($run);

    if ($test_count > 0) {

        //Count how many rows user has on tb_badge_record for the current badge number.
        $sql_check_row = "SELECT COUNT(*) AS count FROM `tb_badge_record` WHERE user_id = ? AND badge_num = ?";
        $run_check_row = $conn->prepare($sql_check_row);

        $run_check_row->bind_param("ii", $user_id, $badge_number);
        $run_check_row->execute();

        $run_check_row_result = $run_check_row->get_result();
        $row = $run_check_row_result->fetch_assoc();

        //If count is 0, no row on table.
        if ($row['count'] == 0) {

            $insert_sql = "INSERT INTO `tb_badge_record` (`user_id`, `badge_num`, `badge_desc`) VALUES (?, ?, ?)";
            $run_insert_sql = $conn->prepare($insert_sql);

            $run_insert_sql->bind_param("iis", $user_id, $badge_number, $badge_desc );

            if ($run_insert_sql->execute()) {
                //echo ("Row inserted to tb_badge_record. (BADGE #4)");
            } else {
                echo "Error inserting row (Badge #4): " . $run_insert_sql->error;
            }

            $run_insert_sql->close();
        } else {
            //echo ("Row already exists on tb_badge_record (BADGE #4).");   
        }
    }

    //Now get the date of first test
    if ($test_count > 0) {

        //php code to select from db
        $sql_for_date = "SELECT * FROM `tb_test_history` WHERE user_id='$user_id' LIMIT 1";

        //echo ("sql in new function: ") . $sql;
        $run_date_sql = mysqli_query($conn, $sql_for_date);
    
        //Find the date in the array
        while($row = mysqli_fetch_array($run_date_sql)){
            $date_first_test = $row['date'];
    
           // echo ("date first test:") . $date_first_test;
        }
    }

    return [$test_count, $date_first_test];

}


/*
    1. It is called from 'badges.php' when checking the status of Badge #14
    2. Will do a count of tb_badge_record to see if you have the maximum awarded (minus account creation and final 'platinum')
    3. If user has 12 rows, it will insert a new row to tb_badge_record, awarding platinum badge.
    4. It will then increment the badge count by 2 to take account of 'account creation (badge #1) and the newest row for 'platinum' (badge #14)
*/
function check_platinum_badge($badge_number) {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    $max_badges = 12; //Remember, minus 1 due to no row for account creation.
    $badge_desc = 'User has earned all badges. Superstar.';

    //Count how many rows user has on tb_badge_record for the current badge number.
    $sql_check_row = "SELECT COUNT(*) AS count FROM `tb_badge_record` WHERE user_id = ?";
    $run_check_row = $conn->prepare($sql_check_row);

    $run_check_row->bind_param("i", $user_id);
    $run_check_row->execute();

    $run_check_row_result = $run_check_row->get_result();
    $row = $run_check_row_result->fetch_assoc();

    $badges_earned = $row['count'];

    //Check if user has the max rows. 
    if ($row['count'] == $max_badges) {

        $insert_sql = "INSERT INTO `tb_badge_record` (`user_id`, `badge_num`, `badge_desc`) VALUES (?, ?, ?)";
        $run_insert_sql = $conn->prepare($insert_sql);

        $run_insert_sql->bind_param("iis", $user_id, $badge_number, $badge_desc );

        if ($run_insert_sql->execute()) {
            //echo ("Row inserted to tb_badge_record. (BADGE #14)");
        } else {
            echo "Error inserting row (Badge #14): " . $run_insert_sql->error;
        }

        $run_insert_sql->close();
    } else {
        //echo ("Row already exists on tb_badge_record. (BADGE #14)");   
    }

    //After inserting row for having 12 rows on table (all other badges but no row for account creation), the row count
    //will now be 13 - just added badge #14 (Platinum)
    $badges_earned++; //add 1
    $badges_earned++; //add another, giving 14 in total, the true max


    //Add row to tb_platinum_user, only ever one row. So first person to get it is special!
    if ($badges_earned == 14) {

        //Count how many rows on tb_platinum_user
        $sql_count_plat = "SELECT COUNT(*) AS count FROM `tb_platinum_user`";
        $run_count_plat = $conn->prepare($sql_count_plat);

       // $run_count_plat->bind_param("ii", $user_id, $badge_number);
        $run_count_plat->execute();

        $run_count_plat_result = $run_count_plat->get_result();
        $cur_row = $run_count_plat_result->fetch_assoc();

        //If count is greater than 0, then skip insert.
        if ($cur_row['count'] == 0) {

            $is_platinum = 'Y';

            $sql_plat = "INSERT INTO `tb_platinum_user` (`user_id`, `is_platinum`) VALUES (?, ?)";
            $run_sql_plat = $conn->prepare($sql_plat);

            $run_sql_plat->bind_param("is", $user_id, $is_platinum );

            if ($run_sql_plat->execute()) {
                //echo ("Row inserted to tb_platinum_user.");
            } else {
                echo "Error inserting row (Platinum User)" . $run_sql_plat->error;
            }

            $run_sql_plat->close();
        } else {
           // echo ("Row already exists on tb_platinum_user.");   
        }

    }

    return $badges_earned;

}


/*
    This function is called as part of Badge #14 processing. It will check the date this badge was awarded on tb_badge_record.
*/
function check_platinum_badge_date($badge_number){

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Select row from tb_badge_record
    $sql_check_row = "SELECT * FROM `tb_badge_record` WHERE user_id = ? AND badge_num = ?";

    $run_check_row = $conn->prepare($sql_check_row);

    $run_check_row->bind_param("ii", $user_id, $badge_number);
    $run_check_row->execute();

    $run_check_row_result = $run_check_row->get_result();
    $row = $run_check_row_result->fetch_assoc();

    //Get date of badge #14 for this user
    $platinum_badge_date = $row['date_awarded'];


    return $platinum_badge_date;

}


/* 
    THIS FUNCTION HAS BEEN SUPERCEDED.THE FUNCTIONALITY IS NOW INCLUDED IN check_user_has_tested()

function find_date_first_test($date)
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //php code to select from db
    $sql = "SELECT * FROM `tb_test_history` WHERE user_id='$user_id' LIMIT 1";

    //echo ("sql in new function: ") . $sql;
    $run = mysqli_query($conn, $sql);

    //Find the date in the array
    while($row = mysqli_fetch_array($run)){
        $date_first_test = $row['date'];

       // echo ("date first test:") . $date_first_test;
    }

    return $date_first_test;
}

*/


/*
    This function is called from the index.php page. 
    It will calculate what the users current days streak is in adding words. 
*/
function find_streak() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //SQL to calculate the streak.
    $sql = "WITH ranked_entries AS ( 
                SELECT date, 
                RANK() OVER (ORDER BY date DESC) AS rnk 
                FROM `tb_vocab` 
                WHERE user_id=? 
                GROUP BY date
                ), 
                streak_data AS ( 
                    SELECT date, 
                        DATEDIFF(CURDATE(), date) AS diff, 
                        rnk 
                        FROM ranked_entries 
                    ) 
                    SELECT COUNT(*) AS streak_days 
                    FROM streak_data 
                    WHERE diff = rnk - 1;";

    //Prepare the statements
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL error (current streak): " . $conn->error);
    }

    //Bind parameter & execute
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    //Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $streak = $row['streak_days'] ?? 0;

    //Close connections
    $stmt->close();
    $conn->close();

    return $streak;

}

/*
    This function is called from the index.php page. 
    It will calculate what the users longest streak of adding words is. 
*/
function find_longest_streak() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //SQL to calculate the streak.
    $sql = "WITH ranked_entries AS (
                SELECT date,
                    DATEDIFF(date, LAG(date) OVER (ORDER BY date)) AS diff
                FROM `tb_vocab`
                WHERE user_id = ?
                GROUP BY date
            )
            SELECT MAX(@streak := IF(diff = 1, @streak + 1, 1)) AS longest_streak
            FROM ranked_entries, (SELECT @streak := 1) init;";

    //Prepare the statements
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL error (longest streak): " . $conn->error);
    }

    //Bind parameter & execute
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    //Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $streak = $row['longest_streak'] ?? 0;

    //Close connections
    $stmt->close();
    $conn->close();

    return $streak;

}


/*
    This is the first cross-user stat.
    Find the user with the most words on tb_vocab.
*/
function am_i_user_with_most_words() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $site_word_leader = 'N';

      //Select row from tb_badge_record
      $sql_most_words = "SELECT user_id, count(*) AS num_words FROM tb_vocab GROUP BY user_id ORDER BY num_words DESC LIMIT 1;";

      $run_most_words = $conn->prepare($sql_most_words);
  
      //$run_most_words->bind_param("i", $user_id);
      $run_most_words->execute();
  
      $run_most_words_result = $run_most_words->get_result();
      $row = $run_most_words_result->fetch_assoc();
  
      //Get users last test score.
      $user_most_words = $row['user_id'];

      if ($user_id == $user_most_words) {
            $site_word_leader = 'Y';
      }

      return $site_word_leader;

}


/*
    This is the second cross-user stat.
    Find the user with the most rows on tb_test_record.
*/
function am_i_user_with_most_tests() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $site_tests_leader = 'N';

    //Select row from tb_badge_record
    $sql_most_tests = "SELECT user_id, count(*) AS num_tests FROM tb_test_record GROUP BY user_id ORDER BY num_tests DESC LIMIT 1;";

    $run_most_tests = $conn->prepare($sql_most_tests);

    //$run_most_words->bind_param("i", $user_id);
    $run_most_tests->execute();

    $run_most_tests_result = $run_most_tests->get_result();
    $row = $run_most_tests_result->fetch_assoc();

    //Get users last test score.
    $user_most_tests = $row['user_id'];

    if ($user_id == $user_most_tests) {
        $site_tests_leader = 'Y';
    }

    return $site_tests_leader;

}


/*
    This is the third cross-user stat.
    Look on tb_platinum_user and see if the user has a row. 
    Only 1 row will ever be on this table, so only one user ever gets this award. The first to get the platinum badge.
*/
function am_i_platinum_user() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $is_platinum_user = 'N';
    $is_userid_plat = 0;


    //Select row from tb_platinum_user
    $sql_is_plat = "SELECT user_id, COUNT(*) AS rowcount FROM tb_platinum_user;";

    $run_is_plat = $conn->prepare($sql_is_plat);

    //$run_most_words->bind_param("i", $user_id);
    $run_is_plat->execute();

    $run_is_plat_result = $run_is_plat->get_result();
    $row = $run_is_plat_result->fetch_assoc();

    if ($row['rowcount'] > 0 ) {
        //Get users last test score.
        $is_userid_plat = $row['user_id'];

        if ($user_id == $is_userid_plat) {
            $is_platinum_user = 'Y';
        }
    }
    
    return $is_platinum_user;

}


