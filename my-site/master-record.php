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


-->

<?php

//Open db connection.
include("include/connection.php");
include("include/error-logging.php");

$record_id = $_GET['id'];
$is_mastered = $_GET['is_mastered'];


if ($is_mastered === "Y") {
    $sql = "UPDATE `tb_vocab` SET `is_mastered`='N' WHERE id='$record_id'";
    $run = mysqli_query($conn, $sql);
} else {
    $sql = "UPDATE `tb_vocab` SET `is_mastered`='Y', `date_mastered`=NOW() WHERE id='$record_id'";
    $run = mysqli_query($conn, $sql);
}

//Manage result of SQL
if($run){
    header("Location: show-data.php?record-mastered");
} else {
    header("Location: show-data.php?record-not-mastered");
}

