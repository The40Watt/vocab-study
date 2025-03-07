

<?php

/*
    This page is called from the 'index.php' page. 
    When the script to create the doughnut chart for the categories, it will call this file directly. 
    This file will query the DB to retrieve the categories used by the user and the number of words in each.
    It will return the inforation in a JSON object.
*/
function get_category_stats()
{

    //Put user_id into session and check on each page to see if the user_id is legit.
    session_start();
    
    //Open DB connection
    include("include/connection.php");

    //Access user_id.
    $user_id = $_SESSION['user_id'];

    $sql_select = "SELECT `category_desc`, COUNT(*) AS `count` FROM `tb_vocab` WHERE user_id='$user_id' GROUP BY `category_desc`";
    $sql_result = $conn->query($sql_select);

    //print_r($sql_select);

    //Initialise arrays
    $x_array = []; //categories
    $y_array = []; //count

    //print_r($x_array);

    if ($sql_result->num_rows > 0) {
        while ($row = $sql_result->fetch_assoc()) {
            $x_array[] = $row['category_desc'];
            $y_array[] = $row['count'];
        }
    }

    $conn->close();

    /*
    echo "<pre>";
    print_r($x_array);
    print_r($y_array);
    echo "</pre>";
    */

return json_encode(['x' => $x_array, 'y' => $y_array]);

}

if (isset($_GET['fetch'])) {
    echo get_category_stats();
}


?>