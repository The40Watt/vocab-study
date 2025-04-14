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


    //Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");

    $sql_avg = "SELECT 
    w.category_desc,
    AVG(w.score) AS average_score
FROM 
    tb_test_words w
JOIN 
    tb_tests t 
ON 
    w.test_id = t.test_id
WHERE 
    t.user_id = ?  
GROUP BY 
    w.category_desc;";

    //Prepare the statement
    $run_avg = $conn->prepare($sql_avg);

$run_avg->bind_param("i", $user_id);
$run_avg->execute();
$run_avg_result = $run_avg->get_result();

// Store data in arrays for Chart.js
$data = [];

while ($row = $run_avg_result->fetch_assoc()) {
    $data[] = [
        'category' => $row['category_desc'],
        'average' => round($row['average_score'], 2)
    ];
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($data);