<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 08-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file contains the logic for updating the table 'tb_user_categories' with the users new category. 

    DETAILS: 
    1. The table 'tb_user_categories' has a rule applied to prevent users entering duplicate categories. 
    2. This page is converting errors into exceptions. By doing so you need to use 'throw / catch' to handle the exceptions. This seems to be the
    only way to get handle the data base error - duplicate entry. 

    

    CHANGE HISTORY:


-->

<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//Open db connection.
include("include/connection.php");
include("include/error-logging.php");
include("include/badge-record-functions.php");

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

$user_id = $_SESSION['user_id'];
$new_category = '';
$new_category_upper = '';

$MYSQLI_CODE_DUPLICATE_KEY = 1062;

//These values are passed in from the 'test.php' file. 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //$new_category = $_GET['category_desc'];
    $new_category = isset($_POST['category_desc']) ? $_POST['category_desc'] : null;
}


//Using try/catch as error messages have been turned into exceptions.
try {
    //Format the input variables to remove potential security attacks.
    $user_id = (int) $user_id;
    $new_category = $conn->real_escape_string($new_category);

    $new_category_upper = strtoupper($new_category); //Force category to be uppercase.

    //SQL to insert new category.
    $sql = "INSERT INTO `tb_user_categories` (`user_id`, `category_desc`) VALUES ($user_id, '$new_category_upper')";
    $conn->query($sql);

    echo "Success: The table has been updated (tb_user_categories).";
    header("Location: user-preferences.php?category-added");

} catch (mysqli_sql_exception $e) {

    if($e->getCode() == 1062) {
        echo ("This is a duplicate entry.");

        header("Location: user-preferences.php?category-not-added");
    } else {
        echo ("DB error: ") . $e->getMessage();
    }
} finally {
    if (isset($conn) && $conn->ping()) {
        $conn->close();
    }


}

