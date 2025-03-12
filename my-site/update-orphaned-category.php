<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 09-03-2025

    HIGHLEVEL DESCRIPTION: 
    This is the logic to assign a new category to 'orphaned' words.

    DETAILS:
    The SQL will update 'tb_vocab'.
    It will return users to 'list-orphaned-words.php'.

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

            $row_id = $_POST['row_id'];
            $category_desc = $_POST['category'];

            $category_desc_upper = strtoupper($category_desc); //Force category to be uppercase.


            //prepare the SQL statement
            $sql = "UPDATE `tb_vocab` SET `category_desc`=? WHERE id=? and user_id=?";
            $run = $conn->prepare($sql);

            //Bind parameters
            $run->bind_param("sii", $category_desc_upper, $row_id, $user_id);
            $run->execute();


            if($run) {
                header("Location: list-orphaned-words.php?category-updated");
            }
    } else {
        header("Location: list-orphaned-words.php?category-update-cancel");
    }
