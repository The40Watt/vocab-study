
<?php


/*
    
    AUTHOR: STEPHEN LENNON
    DATE: 11-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file contains the logic to retreive the users data from 'tb_vocab' and output it to a '.csv' file called 'word_up_vocab_export.csv'.

    DETAILS:
    This file is called from 'user-preferences.php'.
    SQL will select from 'tb_vocab' from the current user only.
    Uses standard file process functions - fopen, fputcsv, fclose
    
*/

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	include("include/error-logging.php");
    include("include/badge-record-functions.php");

	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

    //get user_id to distingush which user is inputting data.
    $user_id = $_SESSION['user_id'];


    // Use prepared statement to fetch data ordered by category
    $sql = "SELECT user_id, fr_text, en_text, category_desc, is_mastered FROM tb_vocab WHERE user_id='$user_id' ORDER BY category_desc ASC, fr_text ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="word_up_vocab_export.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write column headers
    fputcsv($output, ['CATEGORY', 'TARGET LANGUAGE', 'NATIVE LANGUAGE', 'MASTERED (Y/N)']);

    $last_category = null;

    while ($row = $result->fetch_assoc()) {
        // If category changes, write a separator row for clarity
        if ($row['category_desc'] !== $last_category) {
            fputcsv($output, ['CATEGORY: ' . $row['category_desc'], '', '']); // Blank columns for spacing
            $last_category = $row['category_desc'];
        }
        // Write the word entry under the category
        fputcsv($output, ['', $row['fr_text'], $row['en_text'], $row['is_mastered']]); // First column left empty for alignment
}

    // Close file and database connection
    fclose($output);
    $stmt->close();
    $conn->close();
    exit();
?>


