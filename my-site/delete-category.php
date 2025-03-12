<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 08-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file is the logic to delete a word from 'tb_vocab'.

    DETAILS:
    This file is called from 'list-category.php'.

    CHANGE HISTORY:


-->

<?php

//Open db connection.
include("include/connection.php");

$unique_id = $_GET['id'];
$category_desc = $_GET['category_desc'];


$sql = "DELETE FROM `tb_user_categories` WHERE id='$unique_id'";
$run = mysqli_query($conn, $sql);

//Manage result of SQL
if($run){
    header("Location: list-category.php?category-deleted");
} else {
    header("Location: list-category.php?category-not-deleted");
}