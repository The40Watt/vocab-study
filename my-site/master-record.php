<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 28-02-2025

    HIGHLEVEL DESCRIPTION: 
    This file is the logic to mark a word as mastered on tb_vocab.

    DETAILS:
    Called from the 'show-data.php' page. 
    Two variables are passed in via the URL from the 'show-data.php' page - the ID and IS_MASTERED current value. These variables
    will determine if the update is 'Y' or 'N'.


    CHANGE HISTORY:

    06-03-25: Added new code to do a count on tb_vocab where is_mastered is 'Y' after an update is done on tb_vocab. This is part
              of the new calculation of the 'mastery' badges. A new function (update_mastery_badge_records) is then called to 
              complete the badge awarding process.


-->

<?php

//Open db connection.
include("include/connection.php");
include("include/error-logging.php");
include("include/badge-record-functions.php");

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

$record_id = $_GET['id'];
$is_mastered = $_GET['is_mastered'];
$current_date_time = date('Y-m-d H:i:s');
$user_id = $_SESSION['user_id'];


if ($is_mastered === "Y") {
    $sql = "UPDATE `tb_vocab` SET `is_mastered`='N' WHERE id='$record_id'";
    $run = mysqli_query($conn, $sql);
} else {
    $sql = "UPDATE `tb_vocab` SET `is_mastered`='Y', `date_mastered`=NOW() WHERE id='$record_id'";
    $run = mysqli_query($conn, $sql);
    $conn->commit();
}


        //After update table to mark word as mastered, count number of mastered words to see if a badge has been earned. 
        $count_query = "SELECT COUNT(*) AS count FROM `tb_vocab` WHERE user_id='$user_id' AND is_mastered='Y'";
        $count_result = $conn->query($count_query);
        $row = $count_result->fetch_array();
        $mastered_count = $row[0];
        
        //Checking mastery related badges - #10, #11, #12, #13
        //Call new function to insert a row on tb_badge_record when user hits milestone of mastered words.
        switch ($mastered_count) {
            case 1:
                update_mastery_badge_records($mastered_count);
                echo ("this is 1");
                break;
            case 2:
                update_mastery_badge_records($mastered_count);
                echo ("this is 25");
                break;
            case 3:
                update_mastery_badge_records($mastered_count);
                echo ("this is 100");
                break;
            case 4:
                update_mastery_badge_records($mastered_count);
                echo ("this is 250");
                break;
        }
    


//Manage result of SQL

if($run){
    header("Location: show-data.php?record-mastered");
} else {
    header("Location: show-data.php?record-not-mastered");
}
    
