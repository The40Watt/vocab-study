<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 27-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file is validate the token and update the password in the DB.

    DETAILS:
    This file is called from 'reset-password.php'.
    It will be passed in the token in a hidden form field, the token was taken from the URL.
    The token will be validated. 
    If the token is valid, the SQL will be executed to update the password on 'tb_users' and reset the token fields to NULL.


    CHANGE HISTORY:


-->


<?php

    //Open DB connection
    include("include/connection.php");
    include("include/error-logging.php");


    //check again that token is valid.
    $token = $_POST["token"];

    $token_hash = hash("sha256", $token);

    $sql = "SELECT * FROM  tb_users WHERE reset_token_hash=?";


    $run = $conn->prepare($sql);

    $run->bind_param("s", $token_hash);

    $run->execute();

    $result = $run->get_result();

    $user = $result->fetch_assoc();

    if ($user == null) {
        die ("Token not found.");
    }

    if (strtotime($user["reset_token_expires_at"]) <= time()) {
        die ("Token has expired.");
    }


    //If we are here, then token is still valid.
    //Check for non-alphanumeric characters
    if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST["password"])) {

    echo "The string contains non-alphanumeric characters.";
    header("Location: signup.php?wrong-format-username");
    die;
    }

    //Additional validation on password length.
    if (strlen($_POST["password"]) < 8) {
        die("Password must contain at least 8 characters.");
    } elseif (strlen($_POST["password"]) > 20) {
        die("Password must contain less than 20 characters.");
    } else {
        //echo "Password is valid.";
    }

    //Check passwords match.
    if ($_POST["password"] !== $_POST["password_confirmation"]) {
       die("Passwords must match.");
    
    }

    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "UPDATE tb_users SET password=?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id=?";

    $run = $conn->prepare($sql);

    $run->bind_param("ss", $password_hash, $user['id']);

 

    if ($run->execute()) {
        header("Location: login.php?password-reset");
    } else {
        die("Passwords failed to udpate.");
    }