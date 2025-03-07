

<?php

/*
    This page is called from the 'index.php' page. 
    When the script to create the line chart for the users test record. 
    This file will query the DB to retrieve the scores and dates for tests.
    It will return the inforation in a JSON object.
*/
function get_test_results()
{

    //Put user_id into session and check on each page to see if the user_id is legit.
    session_start();
    
    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];


    //Limiting graph to last 10 tests. Order by oldest to newest.
    $sql_select = "SELECT `test_score`, `test_date` FROM `tb_test_record` WHERE user_id='$user_id' ORDER BY `test_date` ASC LIMIT 10";
    $sql_result = $conn->query($sql_select);

    //print_r($sql_select);

    //Initialise arrays
    $x = []; //Dates
    $y = []; //Scores

    //print_r($x_array);

    if ($sql_result->num_rows > 0) {
        while ($row = $sql_result->fetch_assoc()) {
            $formattedDate = date("d-m-Y", strtotime($row['test_date']));
            $x[] = $formattedDate;
            $y[] = $row['test_score'];
        }
    }

    $conn->close();
/*
    echo "<pre>";
    print_r($x);
    print_r($y);
    echo "</pre>";
*/
return json_encode(['x' => $x, 'y' => $y]);

}

if (isset($_GET['fetch'])) {
    echo get_test_results();
}


?>