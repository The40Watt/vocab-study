<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file is called from 'user-preferences.php' when the user inputs numeric values to set a streak. 

    DETAILS:
    File is called from 'user-preferences.php'.
    Will take in two parameters;
        1. test_streak
        2. word_streak
    

    CHANGE HISTORY:

    23-03-25:   Made the two streak input fields be mandatory. Otherwise, if one is left empty, the value on tb_streaks will be updated to zero.


-->
<?php

    //Put user_id into session and check on each page to see if the user_id is legit.
    session_start();

    //open db connection
    include("include/connection.php");
    include("include/error-logging.php");

    $user_id = $_SESSION['user_id'];


    //Connection to db is open, code in connections.php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['test_streak']) && isset($_POST['word_streak']))
        {

                $test_streak = $_POST['test_streak'];
                $word_streak = $_POST['word_streak'];

                //prepare the SQL statement
                $sql = "UPDATE `tb_streaks` SET `max_test_streak`=?, `max_word_streak`=? WHERE user_id=?";
                $run = $conn->prepare($sql);

               // print_r($sql);

                //Bind parameters
                $run->bind_param("iis", $test_streak, $word_streak, $user_id);
                $run->execute();


                if($run) {
                    $run->close();
                    $conn->close();
                    header("Location: user-preferences.php?streaks-updated");
                }
        } else {
            header("Location: user-preferences.php?streaks-not-updated");
        }
    } else {
        echo ("Invalid Request.");
    }
