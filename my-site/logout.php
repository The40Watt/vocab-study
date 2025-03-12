<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This page allows the user to logout of their account. 

    DETAILS:
    Simply unset the user_id variable and redirect the user to the login screen.


    CHANGE HISTORY:

    10-03-25:   Unsetting the variable 'session_tl' holding the users target language. 

-->

<?php

session_start();

if(isset($_SESSION['user_id']))
{
    unset($_SESSION['user_id']);    //unsetting value.
    unset($_SESSION['session_word']);
    unset($_SESSION['session_tl']);
}

header("Location: login.php");
die;