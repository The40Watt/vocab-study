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

    13-03-25:   Added a new section that will update 'tb_tests' and 'tb_test_words'. It is done in one transaction to keep the test_id numbers
                in sync between the two tables. The table 'tb_tests' is also going to replace the table 'tb_test_record'. There is no need for this
                older table any more because we are capturing everything in the two new tables - 'tb_tests' and 'tb_test_words'.

-->

<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//Open db connection.
include("include/connection.php");
include("include/error-logging.php");
include("include/badge-record-functions.php");

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

$user_id = $_SESSION['user_id'];
$selected_category = $_GET['category'];
$test_id = 0;
$UPDATE_SUCCESS = 'Y';

//decode the JSON encode for the vocab_id
$vocab_id = json_decode($_GET['id'],true);
$vocab_cat = json_decode($_GET['cat'],true);

/*
if (isset($_GET['data'])) {

    $jsonData = urldecode($_GET['data']);
    $vocab_id = json_decode($jsonData,true);
}
*/


//These values are passed in from the 'test.php' file. 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $overallScore = isset($_POST['overall_score']) ? $_POST['overall_score'] : null;
    $num_words_tested = isset($_POST['number_words']) ? $_POST['number_words'] : null;
    echo "Received Value: " . htmlspecialchars($overallScore);
    echo "Receieved number of words test: " . htmlspecialchars($num_words_tested);

    //Below are two arrays populated on 'test.php' from looping through the 'dataTable'.
    $words = isset($_POST['word']) ? $_POST['word'] : [];
    $scores = isset($_POST['score']) ? $_POST['score'] : [];

}



   
    try {
        //Open transaction
        $conn->begin_transaction();

        //Trying to enforce UTF-8
	    $conn->set_charset("utf8mb4");

        //Step 1: insert to tb_tests
        $stmt1 = $conn->prepare("INSERT INTO tb_tests (user_id, test_score, num_words, category_desc) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("iiis", $user_id, $overallScore, $num_words_tested, $selected_category);
        $stmt1->execute();        

        if($stmt1->affected_rows == 0) {
            throw new Exception ("Failed to insert into tb_tests.");
        }

        //Get the auto generated test_id from tb_tests from the above transaction
        $test_id = $conn->insert_id;

        //Step 2: Insert into tb_test_words
        $stmt2 = $conn->prepare("INSERT INTO tb_test_words (test_id, word, category_desc, score, vocab_id) VALUES (?, ?, ?, ?, ?)");
  

        for ($i = 0; $i < count($words); $i++) {

            //$vocab_id = $vocab_id['id'];
            //$selected_category = $vocab_id['category_desc'];
            
            $stmt2->bind_param("issii", $test_id, $words[$i], $vocab_cat[$i], $scores[$i], $vocab_id[$i]);
            //$stmt2->bind_param("issii", $test_id, $words[$i], $selected_category, $scores[$i], $vocab_id);
            $stmt2->execute();

            if($stmt2->affected_rows == 0) {
                throw new Exception ("Failed to insert row to tb_test_words.");
            }
        }


        //Commit the transaction
        $conn->commit();
        $UPDATE_SUCCESS = 'Y';

    } catch (Exception $e) {
        //Rollback any query fails
        $conn->rollback();
        echo ("Error on rollback: ") . $e->getMessage();
        $UPDATE_SUCCESS = 'N';
    }

    $stmt1->close();
    $stmt2->close();
    $conn->close();

    //Manage gracefull exit and return to previous file.
    if($UPDATE_SUCCESS == 'Y'){
        header("Location: test.php?test-record-updated");
    } else {
        header("Location: test.php?test-record-update-failed");
    }
    


 
