<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This file is the logic delete a word from tb_vocab.

    DETAILS:
    This file is called from 'show-data.php' when the user presses on the delete icon.
    It will return the user to 'show-data.php' with a message to say that message has been deleted or not. 


    CHANGE HISTORY:


-->

<?php

//Open db connection.
include("include/connection.php");

$record_id = $_GET['id'];

$sql = "DELETE FROM `tb_vocab` WHERE id='$record_id'";
$run = mysqli_query($conn, $sql);

//Manage result of SQL
if($run){
    header("Location: show-data.php?record-delete");
} else {
    header("Location: show-data.php?record-not-deleted");
}