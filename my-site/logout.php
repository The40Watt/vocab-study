<?php

session_start();

if(isset($_SESSION['user_id']))
{
    unset($_SESSION['user_id']);    //unsetting value.
}

header("Location: login.php");
die;