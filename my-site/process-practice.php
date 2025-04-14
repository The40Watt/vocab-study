<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 09-04-2025

    HIGHLEVEL DESCRIPTION: 
    This script will select back from 'tb_vocab' all words that are not mastered in the selected category.

    DETAILS:
    Called from 'practice-words.php'.
    It will populate the '$practice_array' and send it back to the calling php file.

    CHANGE HISTORY:


-->


<?php

    //Open DB connection
    include("include/connection.php");
    include("include/error-logging.php");

    //Put user_id into session and check on each page to see if the user_id is legit.
   // session_start();

    $user_id = $_SESSION['user_id'];



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        //Trying to enforce UTF-8
        mysqli_set_charset($conn, "utf8mb4");
                
        //Declarations
        $category_desc = $_POST['category'];
        $user_id = $_SESSION['user_id'];
        $is_mastered = 'N';

        $stmt = $conn->prepare("SELECT fr_text, en_text FROM `tb_vocab`WHERE user_id=? and category_desc=? and is_mastered=? ORDER BY date DESC");
        $stmt->bind_param("sss", $user_id, $category_desc, $is_mastered);
        $stmt->execute();
        $result = $stmt->get_result();

        $practice_array = []; //Creating array

        while ($row = $result->fetch_assoc()) {
            $practice_array[] = $row; // Add each row into the array
        }

        if (empty($practice_array)) {
            // No results found
            header("Location: practice-words.php?no-words");
            die;
        }
        
        $stmt->close();
        $conn->close();

    }


