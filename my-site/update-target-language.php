<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 10-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file is called from 'user-preferences.php' when the user selects a target language from the drop-down menu.

    DETAILS:
    This is a simple update to 'tb_users' to set the 'tl_code' field to what ever the user has choosen from the drop-down menu. 

    CHANGE HISTORY:


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

        if(isset($_POST['lang_code']))
        {

                //$lang_code = $_POST['lang_code'];

                $lang_code = $_POST['lang_code'];

                //prepare the SQL statement
                $sql = "UPDATE `tb_users` SET `tl_code`=? WHERE user_id=?";
                $run = $conn->prepare($sql);

                print_r($sql);

                //Bind parameters
                $run->bind_param("si", $lang_code, $user_id);
                $run->execute();


                if($run) {
                    $run->close();
                    $conn->close();
                    header("Location: user-preferences.php?language-updated");
                }
        } else {
            header("Location: user-preferences.php?language-not-updated");
        }
    } else {
        echo ("Invalid Request.");
    }
