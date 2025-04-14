<?php

/**
 *      This file is called from 'index.php' to get data for the guage graph showing the users current testing streak vs. the max streak they've set.
 * 
 *      The max_test_streak value is found on 'tb_streaks'.
 *      The current_test_streak value is calculated on 'index.php' already in a function call.
 */

    //Put user_id into session and check on each page to see if the user_id is legit.
    session_start();

    include("include/connection.php");

    //set user_id so we can only see which words the logged in user has entered.
    $user_id = $_SESSION['user_id'];


    //php code to select from db
    $sql = "SELECT max_test_streak FROM `tb_streaks` WHERE user_id='$user_id'";
    $run = mysqli_query($conn, $sql);


    //Check for errors on sql query
    if (!$run) {
        echo "Error accessing tb_streaks (tests): " . mysqli_error($conn);
    } 

    if ($run->num_rows > 0) {

        while ($row = $run->fetch_assoc()) {
            $max_test_streak = $row['max_test_streak'];
        }
    } else {
        $max_test_streak = 0;
    }

 
    $data = [
        'currentStreak' => 5,   //This value is overwritten in index.php when chart is prepared.
        'maxStreak' => $max_test_streak
    ];

    //Return as JSON
    header('Content-Type: application/json');
    echo json_encode($data);