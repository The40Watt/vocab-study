<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 13-03-2025

    HIGHLEVEL DESCRIPTION: 


    DETAILS:


    CHANGE HISTORY:


-->
<?php

    //Put user_id into session and check on each page to see if the user_id is legit.
    session_start();

    //open db connection
    include("include/connection.php");
    include("include/error-logging.php");

    //Declare variables
    $user_id = $_SESSION['user_id'];
    $currentDateTime = date("Y-m-d H:i:s");
    $set_yes = YES;

    //decode the JSON encode for the vocab_id
    $vocab_id = json_decode($_GET['vocab_id'],true);

    $stmt2 = $conn->prepare("UPDATE `tb_vocab` SET `is_mastered`=?, `date_mastered`=? WHERE user_id=? AND id=?");

    for ($i = 0; $i < count($vocab_id); $i++) {
        $stmt2->bind_param("ssii", $set_yes, $currentDateTime, $user_id, $vocab_id[$i]);
        $stmt2->execute();

        //Handle error.
        if($stmt2->affected_rows == 0) {
            header("Location: index.php?words-not-mastered");
            die;
        }
    }

    if($stmt2) {
                header("Location: index.php?words-mastered");
    } else {
        header("Location: index.php?words-not-mastered");
    }
