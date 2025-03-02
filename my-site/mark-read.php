<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This page allows an admin user to mark messages on tb_message as read.

    DETAILS:
    SQL call to update tb_message and set the 'read_msg' column to 'N'.

    CHANGE HISTORY:


-->
<?php

    //open db connection
    include("include/connection.php");

    //this will ensure PHP displays all errors
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

    $sql = "UPDATE `tb_message` SET `read_msg`='N'";

    //Execute SQL and check for errors
    if (!mysqli_query ($conn, $sql)) {
        echo ("SQL Error: ") . $sql . "<br>" . mysqli_error($conn);
    } else {
        //success, so redirect the user
        header("Location: index.php?messages-read");
        die;            
    }
