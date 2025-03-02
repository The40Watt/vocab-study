<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 28-02-2025

    HIGHLEVEL DESCRIPTION: 
    Wil direct the user to the appropriate page.

    DETAILS:
    Logic behind the quick link buttons on the main index page. 

    CHANGE HISTORY:
    

-->
<?php

    //open db connection
    include("include/connection.php");

    //Connection to db is open, code in connections.php
if(isset($_POST['AddWordButton']))
{
    header("Location: input.php");
} elseif (isset($_POST['ViewListButton'])) {
    header("Location: show-data.php");
} elseif (isset($_POST['ViewBadgesButton'])) {
    header("Location: badges.php");
} else {
    header("Location: test.php");
}