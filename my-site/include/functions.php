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

// full code at timestamp 31:25
function random_num($length)
{
    $text = "";

    //Checking lenght is less than 5 and setting it to 5 if not
    if($length < 5)
    {
        $length = 5; 
    }

    $len = rand(4, $length);

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

    //PHP code to select next word to be tested
    $sql = "SELECT * FROM `tb_vocab` WHERE user_id='$user_id' ORDER BY test_count ASC, date DESC LIMIT 1";
    $run = mysqli_query($conn, $sql);

    //Find the word in the arry
    while($row = mysqli_fetch_array($run)){
         $next_word = $row['fr_text'];
    }

    return $next_word;
}

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
function find_date_first_word($date)
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


function date_of_signup($date)
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
function check_messages($message_alert) {

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
    Function to check if the user has started a test. 
    Check tb_vocab for user where test_count is greater than 0.
*/
function check_tested($tested) {

    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];

    //Prepare SQL and execute
    $sql = "SELECT * FROM tb_vocab WHERE user_id='$user_id' AND test_count > 0";
    $run = mysqli_query($conn, $sql);

    //Number of rows found with test_count greater than 0 on tb_vocab
    $test_count = mysqli_num_rows($run);

    //Return count.
    return $test_count;

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
    Function to check if user has a row on tb_message.
    Takes in two parameters and will pass back if there is a row on the table
    and the date.
*/
function did_reach_out($contacted, $date) 
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];
    
    //php code to select from db
    $sql = "SELECT * FROM `tb_message` WHERE id_user='$user_id' ORDER BY date ASC LIMIT 1";
    $run = mysqli_query($conn, $sql);

    $has_contacted = mysqli_num_rows($run);

    //Find the date in the array
    while($row = mysqli_fetch_array($run)){
        $message_date = $row['date'];
    }

    return [$has_contacted, $message_date];

}


/*
    Function gather categories user has used. 
    Recode lates date of selection.
*/
function date_last_category($category_date) {

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
    Function to select date from tb_message_history for a user. 
    Will only ever be 1 row on this table per user. 
*/

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


/* 
    This function will retrieve the last five words the user has entered. 
*/
function last_five_words() {
    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //PHP code to select next word to be tested
    $sql = "SELECT fr_text FROM `tb_vocab` WHERE user_id='$user_id' ORDER BY date DESC  LIMIT 5";
    $run = mysqli_query($conn, $sql);


    //Check for errors on sql query
	if (!$run) {
		echo "Error: " . mysqli_error($conn);
	} elseif (mysqli_num_rows($run) > 0) {
		//echo "Select successful, found " . mysqli_num_rows($run) . " rows.";
	} else {
		echo "There is a problem words for user.";
	}

   return $run;
}