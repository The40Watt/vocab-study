<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 05-04-2025

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

    $user_id = $_SESSION['user_id'];

    //Connection to db is open, code in connections.php
    if(isset($_POST['SubmitButton']))
    {

        //Check if checkbox was ticked
        if (isset($_POST['case_sensitive']) && $_POST['case_sensitive'] === 'Y') {
            $new_value = 'Y';
        } else {
            $new_value = 'N';
        }


            //prepare the SQL statement
            $sql_pref = "UPDATE `tb_user_pref` SET `is_case_sensitive`=? WHERE user_id=?";
            $run_pref = $conn->prepare($sql_pref);

            //Bind parameters
            $run_pref->bind_param("si", $new_value, $user_id);
            $run_pref->execute();


            if($run_pref) {
                header("Location: user-preferences.php?case-sens-updated");
            }
    } else {
        header("Location: user-preferences.php?case-sens-failed-updated");
    }