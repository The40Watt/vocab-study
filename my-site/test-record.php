<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 06-03-2025

    HIGHLEVEL DESCRIPTION: 
    When a user takes a test and choose to save the result this file will be called to store the result on 'tb_test_record'.

    DETAILS:
    Called from 'test.php' when the user wants to save the result. 
    Will take in two parameters - 1)the test score, 2) the number of words tested.
    Will return user to 'test.php' when processing is finished.


    CHANGE HISTORY:

-->

<?php

//Open db connection.
include("include/connection.php");
include("include/error-logging.php");
include("include/badge-record-functions.php");

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

$user_id = $_SESSION['user_id'];
$selected_category = $_GET['category'];



//These values are passed in from the 'test.php' file. 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $overallScore = isset($_POST['overall_score']) ? $_POST['overall_score'] : null;
    $num_words_tested = isset($_POST['number_words']) ? $_POST['number_words'] : null;
    echo "Received Value: " . htmlspecialchars($overallScore);
    echo "Receieved number of words test: " . htmlspecialchars($num_words_tested);
}

//insert a row to tb_test_record as user has chosen to save their score. 
$insert_sql = "INSERT INTO `tb_test_record` (`user_id`, `test_score`, `num_words`, `category_desc`) VALUES (?, ?, ?, ?)";
$run_insert_sql = $conn->prepare($insert_sql);

$run_insert_sql->bind_param("iiis", $user_id, $overallScore, $num_words_tested, $selected_category );

if ($run_insert_sql->execute()) {
    echo ("Row inserted to tb_test_record.");
} else {
    echo "Error inserting row: " . $run_insert_sql->error;
}

$run_insert_sql->close();


//Manage result of SQL
if($run_insert_sql){
    header("Location: test.php?test-record-updated");
} else {
    header("Location: test.php?test-record-update-failed");
}

