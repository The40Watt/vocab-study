<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 09-03-2025

    HIGHLEVEL DESCRIPTION: 
    File holds the logic for updating tb_user_categories with category edits. 

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

            $row_id = $_POST['row_id'];
            $category_desc = $_POST['category_desc'];

            $category_desc_upper = strtoupper($category_desc); //Force category to be uppercase.




            //prepare the SQL statement
            $sql = "UPDATE `tb_user_categories` SET `category_desc`=? WHERE id=? and user_id=?";
            $run = $conn->prepare($sql);

            //Bind parameters
            $run->bind_param("sii", $category_desc_upper, $row_id, $user_id);
            $run->execute();


            if($run) {
                header("Location: list-category.php?category-updated");
            }
    } else {
        header("Location: list-category.php?category-update-cancel");
    }
