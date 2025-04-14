<?php

function check_login($conn)
{

    //See timestamp 23:00 of video for full code.

   if(isset($_SESSION['user_id'])) //check if session value exists. If it does then check it is valid, if not, redirect.
   {

    $id = $_SESSION['user_id'];
    $query = "select * from tb_users where user_id = '$id' limit 1";

    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0)
    {
        //if result is postive and number of rows is greater than 0
        $user_data = mysqli_fetch_assoc($result);
        return $user_data;
    }

   }

   //redirect to login if failed to retrieve user details
   header("Location: login.php"); 
   die;
}

/***
 * CHANGE HISTORY: 
 * 
 * 07-04-25: Change to try ensure code created is minimum 5 digits.
 */
function random_num($length)
{
    $text = "";

    //Checking lenght is less than 5 and setting it to 5 if not
    if($length < 5)
    {
        $length = 5; 
    }

    $len = rand(5, $length); //now 5, not 4

    for ($i=0; $i < $len; $i++) {
        
        $text .= rand(0,9); //random number between 0 and 9
    }

    return $text;
}

//Count number of rows a user has on the tb_vocab table.
function count_records($cnt)
{

	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id'";;
    $run = mysqli_query($conn, $sql);

    $rowcount = mysqli_num_rows($run);

    return $rowcount;
}

/* This function will find the next word that the user is due to test, i.e. the word with
the lowest 'test_count' value. */
function next_word($word) {
    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $next_word = '';

    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    //PHP code to select next word to be tested
    $sql = "SELECT * FROM `tb_vocab` WHERE user_id='$user_id' ORDER BY test_count ASC, date DESC LIMIT 1";
    $run = mysqli_query($conn, $sql);

    //Find the word in the arry
    while($row = mysqli_fetch_array($run)){
         $next_word = $row['fr_text'];
    }

    return $next_word;
}

/**
 *  This function is used to sum up the value in test_count on 'tb_vocab'. That will give you the total number of words tests. 
 */
function number_of_tests($test_cnt) {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //PHP code to sum up tests of each word for a user
    /*$sql = "SELECT SUM(`test_count`) FROM `tb_vocab` WHERE user_id='$user_id'";
    echo $sql;
    $run = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo $row;
    $tested_count = $row['value_sum'];

    return $tested_count;*/

    // Create connection by passing these connection parameters

//sql query
$sql = "SELECT SUM(`test_count`) FROM `tb_vocab` WHERE user_id='$user_id'";

$result = $conn->query($sql);
//echo $result;
//display data on web page
while($row = mysqli_fetch_array($result)){

    $tested_count = $row['SUM(`test_count`)'];
}


   // $result = mysqli_query($conn, 'SELECT SUM(value) AS value_sum FROM codes'); 
   // $row = mysqli_fetch_assoc($result); 
    //$sum = $row['value_sum'];
    return $tested_count;
}


//Count number of rows a user has on the tb_vocab table.
function count_records_by_category(String $input)
{

	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and category_desc='$input'";
    $run = mysqli_query($conn, $sql);

    $category_rowcount = mysqli_num_rows($run);

    return $category_rowcount;
}

//Find date of earliest word on tb_vocab table.
//Used in the badges screen. 
function find_date_first_word()
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY date ASC limit 1";;
    $run = mysqli_query($conn, $sql);

    //Find the date in the array
    while($row = mysqli_fetch_array($run)){
        $date_first_word = $row['date'];
    }

    return $date_first_word;
}


function date_of_signup()
{
    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //php code to select from db
    $sql = "SELECT * FROM `tb_users`WHERE user_id='$user_id' limit 1";
    $run = mysqli_query($conn, $sql);

    //Find the date in the array
    while($row = mysqli_fetch_array($run)){
        $signedup_datetime = $row['date'];
    }

    return $signedup_datetime;
}

/*  
    Function to check for new rows on tb_messages. 
    If so, it will be highlighted to admin users on the main page. 
*/
function check_messages() {

    //Open DB connection
    include("include/connection.php");

    //Prepare SQL and execute
    $sql = "SELECT * FROM `tb_message` WHERE read_msg = 'Y'";
    $run = mysqli_query($conn, $sql);

    //Number of rows found with 'Y' on tb_message
    $message_rowcount = mysqli_num_rows($run);

    //Return count.
    return $message_rowcount;

}




//Find date of earliest word on tb_vocab table.
//Used in the badges screen. 
/*function find_date_twentyfive_word($date)
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY date ASC limit 25";
    $run = mysqli_query($conn, $sql);

    //Need to find value of last row (should be 25).
    $lastrow = null;
    while($row = mysqli_fetch_array($run)){
        $lastrow = $row;
    }

    $date_twentyfifth_word = $lastrow['date'];

    return $date_twentyfifth_word;
}*/

//Find date of earliest word on tb_vocab table.
//Used in the badges screen. 
//Passing in milestone value, i.e. 25 words, or 100 words or 250 words input. This will dicate number of rows returned in sql.
function find_date_milestone_word($milestone, $date)
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //php code to select from db
   /* if ($milestone == 25) {

        $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY date ASC limit 25";
        $run = mysqli_query($conn, $sql);
    } elseif ($milestone == 100) {
        $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY date ASC limit 100";
        $run = mysqli_query($conn, $sql);
    }*/

    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY date ASC limit $milestone";
    $run = mysqli_query($conn, $sql);

    //Need to find value of last row.
    $lastrow = null;
    while($row = mysqli_fetch_array($run)){
        $lastrow = $row;
    }

    $date_milesstone_word = $lastrow['date'];

    return $date_milesstone_word;
}





/*
    Function gather categories user has used. 
    Recode lates date of selection.
*/
function date_last_category() {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];

//SELECT DISTINCT max(date)  FROM tb_vocab WHERE user_id='143436958';
    //Prepare SQL and execute
    $sql = "SELECT DISTINCT MAX(date) as max_date FROM tb_vocab WHERE user_id='$user_id'";

    $run = mysqli_query($conn, $sql);

    //Find the date in the array
    while($row = mysqli_fetch_array($run)){
        $newest_category = $row['max_date'];
    }

    return $newest_category;
}





/* 
    This function will retrieve the last five words the user has entered.
    
    CHANGE HISTORY

    04-04-25: Live fix. Adding line of code to force UTF-8.
*/
function last_five_words() {
    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $user_no_words = 'Y';

    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    //PHP code to select last five words created
    $sql = "SELECT fr_text FROM `tb_vocab` WHERE user_id='$user_id' ORDER BY id DESC  LIMIT 5";
    $run = mysqli_query($conn, $sql);


    //Check for errors on sql query
	if (!$run) {
		echo "Error: " . mysqli_error($conn);
	} elseif (mysqli_num_rows($run) > 0) {
		//echo "Select successful, found " . mysqli_num_rows($run) . " rows.";
	}


   return $run;
}


/*
    This function will retrive the last 5 words marked as mastered from tb_vocab.
    It is called from 'index.php'

    CHANGE HISTORY

    04-04-25: Live fix. Adding line of code to force UTF-8.
*/
function last_five_mastered() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    
    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    //PHP code to select last five words to be mastered.
    $sql = "SELECT fr_text FROM `tb_vocab` WHERE user_id='$user_id' AND is_mastered='Y' ORDER BY date_mastered DESC  LIMIT 5";
    $run = mysqli_query($conn, $sql);


    //Check for errors on sql query
    if (!$run) {
        echo "Error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($run) > 0) {
        //echo "Select successful, found " . mysqli_num_rows($run) . " rows.";
    } 

    return $run;

}

/*
    This function will retrive the last 5 words marked as mastered from tb_vocab.
    It is called from 'index.php'
*/
function count_mastered_words() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $mastered_cnt = 0;


    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND is_mastered='Y'";
    $run = mysqli_query($conn, $sql);


    //Check for errors on sql query
    if (!$run) {
        echo "Error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($run) > 0) {
        //echo "Select successful, found " . mysqli_num_rows($run) . " rows.";
        //$mastered_cnt = mysqli_num_rows($run);
    } 

    $mastered_cnt = mysqli_num_rows($run);

    return $mastered_cnt;
}


/*
    num1 is number of words. num2 is number of mastered words. Will calc the percentage.
*/
function calc_percentage_mastered($num1, $num2) {

    //Declare variable
    $calc_result = 0;

    //stop division by 0 (if there are no mastered words, num2 will be 0)
    if ($num2 > 0) {
        $calc_result = (($num2 / $num1) * 100);

        //intval will remove decimal places.
        return intval($calc_result);
    } else {
        //intval will remove decimal places.
        return 0;
    }

}

/*
    Function to calculate the percentage of words not tested from all the words
    a user has.
*/
function calc_percentage_not_tested($num1, $num2) {
    
    //Declare variable
    $calc_result = 0;

    $calc_result = (($num2 / $num1) * 100);


    //intval will remove decimal places.
    return intval($calc_result);

}


/*
    Function to count the number of words a user has that has a test_count of zero.

    CHANGE HISTORY:

    15-03-25:   Changed echo message to be more clear. SQL is working fine but user just has no words on 'tb_vocab'
                with a test_count of zero. It is not an error condition. 
*/
function number_not_tested() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $not_tested_cnt = 0;


    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND test_count=0";
    $run = mysqli_query($conn, $sql);


    //Check for errors on sql query
    if (!$run) {
        echo "Error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($run) > 0) {
        //echo "Select successful, found " . mysqli_num_rows($run) . " rows.";
        $not_tested_cnt = mysqli_num_rows($run);

    } else {
        //echo "SQL executed correctly but user has no words with test_count of zero. Not an error condition.";
    }

    return $not_tested_cnt;

}

/*
    This function is to calculate the average number of days between adding a new word and 
    marking it as 'mastered'. It select all rows from tb_vocab for a user where is_mastered
    is 'Y'. It will loop through array and calculat the difference in each date and total up
    the days, then divide by the number of rows.
*/
function calc_average_time_mastery() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    $is_mastered = 'Y';
    $total_days = 0;
    $average_days = 0;

    //prepare the SQL statement
    $sql = "SELECT `date`, `date_mastered` FROM `tb_vocab` WHERE user_id='$user_id' AND is_mastered='$is_mastered' ORDER BY id ASC ";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {

        //Loop through array to calc difference in dates.
        while ($row = $result->fetch_assoc()) {
            $date1 = new DateTime($row['date']);
            $date2 = new DateTime($row['date_mastered']);

            $interval = $date1->diff($date2);

            $total_days += $interval->days;
        }
    } else {
        //echo ("No information found. (calc_average_time_mastery function)");
        return 0;
    }

    //Calc average (total days / number of rows)
    //Avoid division by zero error
    if ($total_days > 0) {

        $average_days = $total_days / $result->num_rows;

        return intval($average_days);
    } else {
        return 0;
    }

}


/*
    Function to count the number of categories a user has used. 
    If they have used all 9, they will earn a badge.
*/
function count_categories($category_count) {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];
    $num_categories = 0;

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

    return $num_categories;
}



/*
    Called from 'index.php'
    It will retrive from 'tb_test_record' the users most tested category. 

    Change History: 

    13-03-25:   This stat is now derived from 'tb_tests', not 'tb_test_record'.
*/
function find_most_test_category() {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];

    $most_used_category = '';


    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    //Select row from tb_badge_record
    $sql_cat_count = "SELECT
                            category_desc,
                        COUNT(category_desc) AS `value_occurrence` 
                        FROM
                            `tb_tests` WHERE user_id=?
                        GROUP BY 
                            category_desc
                        ORDER BY 
                            `value_occurrence` DESC
                        LIMIT 1;";

    $run_cat_count = $conn->prepare($sql_cat_count);

    $run_cat_count->bind_param("i", $user_id);
    $run_cat_count->execute();

    $run_cat_count_result = $run_cat_count->get_result();
    $row = $run_cat_count_result->fetch_assoc();


    //Check number of rows. Otherwise will throw an error on index.php if user has no row.
    $num_rows = $run_cat_count_result->num_rows;

    if ($num_rows > 0) {
        //Get users most tested category
        $most_used_category = $row['category_desc'];
    }

    return $most_used_category;

}

/*
    Called from 'index.php'
    It will retrive from 'tb_test_record' the users last test score. 

    Change History: 

    13-03-25:   This stat is now derived from 'tb_tests', not 'tb_test_record'.
*/
function get_last_test_score() {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];

    $last_test_score = 0;

    //Select row from tb_badge_record
    $sql_last_score = "SELECT * FROM `tb_tests` WHERE user_id=? ORDER BY test_date DESC LIMIT 1;";

    $run_last_score = $conn->prepare($sql_last_score);

    $run_last_score->bind_param("i", $user_id);
    $run_last_score->execute();



    $run_last_score_result = $run_last_score->get_result();
    $row = $run_last_score_result->fetch_assoc();

    //Check number of rows. Otherwise will throw an error on index.php if user has no row.
    $num_rows = $run_last_score_result->num_rows;

    if ($num_rows > 0) {
        //Get users last test score.
        $last_test_score = $row['test_score'];
    }

    return $last_test_score;

}

/*
    Rather than having the SQL in individual files to lookup the users categories, it has been moved into this function. 

*/
function populate_category_dropdown() {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];

    //Get data from ct_categories, used to populate teh drop-down form. SQL has a union with 'tb_user_category' to get user created categories. 
    //$sql = "SELECT category_desc, id from `ct_categories` UNION SELECT category_desc, id FROM `tb_user_category` WHERE user_id='$user_id' ORDER BY category_desc ASC";

    //Trying to enforce UTF-8
    $conn->set_charset("utf8mb4");

    $sql = "SELECT category_desc, id FROM `tb_user_categories` WHERE user_id='$user_id' ORDER BY category_desc ASC";
    $result = mysqli_query($conn, $sql);

    //Check for errors on sql query
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($result) > 0) {
        //echo "Select successful, found " . mysqli_num_rows($result) . " rows.";
    } else {
        echo "There is a problem finding the list of categories.";
    }

    return $result;

}



/*
    Rather than having the SQL in individual files to lookup the users categories, it has been moved into this function. 

*/
function populate_language_dropdown() {

    //Open DB connection
    include("include/connection.php");

    $sql = "SELECT * FROM `ct_languages` ORDER BY id ASC";
    $lang_result = mysqli_query($conn, $sql);

    //Check for errors on sql query
    if (!$lang_result) {
        echo "Error: " . mysqli_error($conn);
    } elseif (mysqli_num_rows($lang_result) > 0) {
        //echo "Select successful, found " . mysqli_num_rows($result) . " rows.";
    } else {
        echo "There is a problem finding the list of languages.";
    }

    return $lang_result;

}

/*
    CAN THIS BE DELETED?
*/
function populate_user_created_category_dropdown() {

    //Open DB connection
    include("include/connection.php");
    
    //Access user_id.
    $user_id = $_SESSION['user_id'];
    
    //Get data from ct_categories, used to populate teh drop-down form. SQL has a union with 'tb_user_category' to get user created categories. 
    $sql = "SELECT category_desc, id from `tb_user_category` WHERE user_id='$user_id' ORDER BY category_desc ASC";
    
      $result = mysqli_query($conn, $sql);
    
      //Check for errors on sql query
      if (!$result) {
          echo "Error: " . mysqli_error($conn);
      } elseif (mysqli_num_rows($result) > 0) {
          //echo "Select successful, found " . mysqli_num_rows($result) . " rows.";
      } else {
          echo "There is a problem finding the list of categories.";
      }
    
      return $result;
    
}


/*
    This is a function to find the target language of the logged in user. 
*/
function find_users_tl() {

    //Open DB connection
    include("include/connection.php");
    
    //Access user_id.
    $user_id = $_SESSION['user_id'];

    //Declare variable
    $user_tl = '';

    $sql_find_tl = "SELECT tl_code FROM `tb_users` WHERE user_id='$user_id'";

    $run_find_tl = $conn->prepare($sql_find_tl);

    $run_find_tl->execute();

    if($run_find_tl) {
        $run_find_tl_result = $run_find_tl->get_result();
        $row = $run_find_tl_result->fetch_assoc();

        //Get value
        $user_tl = $row['tl_code'];

        $conn->close();
        $run_find_tl->close();

        return $user_tl;
    } else {
        echo ("Error finding target language. ");
    }

}

/*
    This is a function to find the is_premium value on 'tb_user'. 
*/
function is_user_premium() {

    //Open DB connection
    include("include/connection.php");
    
    //Access user_id.
    $user_id = $_SESSION['user_id'];

    //Declare variable
    $is_premium = '';

    $sql_find_prem = "SELECT is_premium FROM `tb_users` WHERE user_id='$user_id'";

    $run_find_prem = $conn->prepare($sql_find_prem);

    $run_find_prem->execute();

    if($run_find_prem) {
        $run_find_prem_result = $run_find_prem->get_result();
        $row = $run_find_prem_result->fetch_assoc();

        //Get value
        $is_premium = $row['is_premium'];

        $conn->close();
        $run_find_prem->close();

        return $is_premium;
    } else {
        echo ("Error finding target language. ");
    }

}



/*
    Function to return a greeting in the users target language.
*/
function get_user_greeting($lang_code) {

    $greetings_array = [
        'en' => 'Hello',
        'fr' => 'Bonjour',
        'es' => 'Hola',
        'de' => 'Hallo',
        'it' => 'Ciao',
        'pt' => 'Olá',
        'ru' => 'Здравствуйте',
        'zh' => '你好',
        'ja' => 'こんにちは',
        'ko' => '안녕하세요',
        'ar' => 'مرحبا',
        'hi' => 'नमस्ते' ];

        //Return the greeting if the lang_code exists in the array, defaulting to english
        return $greetings_array[$lang_code] ?? $greetings_array['en'];

}



/**
 * 
 */
function find_cloud_words() {

    include("include/connection.php");
    include_once("include/constants.php");




    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Set variables
    // $max_score = HIGHSCORE;
    // $occurence = 1;
    // $mastered = NO;

    // $sql = "SELECT tw.word, tw.vocab_id
    //         FROM tb_test_words tw
    //         JOIN tb_vocab v ON tw.vocab_id = v.id
    //         WHERE tw.score >= ?
    //         AND v.is_mastered = ?
    //         AND v.user_id=?
    //         GROUP BY tw.vocab_id, tw.word
    //         HAVING COUNT(tw.vocab_id) > ?;";
	
    
    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    $sql = "SELECT fr_text from `tb_vocab` WHERE user_id=? ORDER BY RAND() LIMIT 30";

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
    $run_sql->bind_param("i", $user_id );

    if(!$run_sql->execute()) {
        echo ("Error finding words for cloud: ") . $run_sql->error;
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
    Allowing users to chose between tests being case sensitive or case in-sensitive. 
*/
function check_case_sensitivity() {

    //Open DB connection
    include("include/connection.php");
    
    //Access user_id.
    $user_id = $_SESSION['user_id'];

    //Declare variable
    $case_sensitive = '';

    $sql_find_sens = "SELECT is_case_sensitive FROM `tb_user_pref` WHERE user_id='$user_id'";

    $run_find_sens = $conn->prepare($sql_find_sens);

    $run_find_sens->execute();

    if($run_find_sens) {
        $run_find_sens_result = $run_find_sens->get_result();
        $row = $run_find_sens_result->fetch_assoc();

        //Get value
        $case_sensitive = $row['is_case_sensitive'];

        $conn->close();
        $run_find_sens->close();

        return $case_sensitive;
    } else {
        echo ("Error finding case sensitivity value. ");
    }

}

