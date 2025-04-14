<?php

/***
 *  Called from index.php as part of the 'Admin' tab.
 * 
 *  CHANGE HISTORY: 
 * 
 *  07-04-25:   LIVE FIX. Added 'where' clause to SQL to have more accurate stats, so it doesn't include any test data. Also using newly created constant holding the live date.
 */
function find_number_registered_users()
{
	include("include/connection.php");
    include_once("include/constants.php");


    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare variable
    $num_reg_users = 0;

    //php code to select from db
    $sql = "SELECT * FROM `tb_users` WHERE date >'" . LIVEDATE . "' ";

    $run = mysqli_query($conn, $sql);

    $num_reg_users = mysqli_num_rows($run);

    $conn->close();
    $run->close();

    return $num_reg_users;
}


/***
 *  Called from index.php as part of the 'Admin' tab.
 * 
 *  CHANGE HISTORY: 
 * 
 *  07-04-25:   LIVE FIX. Added 'where' clause to SQL to have more accurate stats, so it doesn't include any test data. Also using newly created constant holding the live date.
 */
function find_total_words_in_db()
{
	include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare variable
    $total_words_in_db = 0;

    //php code to select from db
    $sql = "SELECT * FROM `tb_vocab` WHERE date >'" . LIVEDATE . "'";

    $run = mysqli_query($conn, $sql);

    $total_words_in_db = mysqli_num_rows($run);

    $conn->close();
    $run->close();

    return $total_words_in_db;

}


/***
 *  Called from index.php as part of the 'Admin' tab.
 * 
 *  CHANGE HISTORY: 
 * 
 *  07-04-25:   LIVE FIX. Added 'where' clause to SQL to have more accurate stats, so it doesn't include any test data. Also using newly created constant holding the live date.
 */
function find_total_tests_in_db() {

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];

    //Declare variable
    $total_tests_in_db = 0;

    //php code to select from db
    $sql = "SELECT * FROM `tb_tests` WHERE test_date >'" . LIVEDATE . "'";

    $run = mysqli_query($conn, $sql);

    $total_tests_in_db = mysqli_num_rows($run);

    $conn->close();
    $run->close();

    return $total_tests_in_db;
}