<?php

  /*
  AUTHOR: STEPHEN LENNON
  DATE: 19-02-2025

  HIGHLEVEL DESCRIPTION: 
  This file contains the logic for creating a new user on the 'tb_users' table, i.e. inserting a row. 

  DETAILS:


  CHANGE HISTORY:
  09-03-25: When a new row has been successfully added to 'tb_users', the rows on the 'tb_categories' table are copyied into a new table called 'tb_user_categories'. 
            This will allow the user to keep their own record of categories and add / delete / edit them as they please without impacting on other users. 
  
  23-03-25: Changed SQL on insert to 'tb_user' to put 'XX' into tl_code as a default value. This will avoid issues with NULL when checking field on index.php
  
            Added extra SQL insert statement. Now adding row to 'tb_streaks'.

  24-03-25: Added two new fields to 'tb_user' - email and is_premium.
  
  */

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	  include("include/connection.php");
    include("include/functions.php");

    //this will ensure PHP displays all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        //Something was posted
        //collect the data from the POST variable
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];
        $user_email = $_POST['user_email'];

        $string = $user_name;


        //Check for non-alphanumeric characters
        if (!preg_match('/^[a-zA-Z0-9]+$/', $string)) {
      
            echo "The string contains non-alphanumeric characters.";
            header("Location: signup.php?wrong-format-username");
            die;
        }

        //Check username exists already
        if (!empty($user_name)) {
          //read from database, get user.
          $query = "select * from tb_users where user_name = '$user_name'";

          $run = mysqli_query($conn, $query);

          $username_row = mysqli_num_rows($run);

          if ($username_row > 0) {
            echo "The string contains non-alphanumeric characters.";
            header("Location: signup.php?username-exists");
            die;
          }
        }

        //if username and password not empty
        if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
        {
            $user_id = random_num(20); //create a random user_id

            //Admin_user is a mandatory field to fill. Also setting defautl TL
            $admin_user = 'N';
            $user_tl = 'XX'; 
            $is_premium = 'Y'; //Current setting everyone as premium.

            //Setting valus for streaks
            $max_test_streak = 7;
            $max_word_streak = 7;

            $query = "insert into tb_users (user_id, user_name, password, email, admin_user, tl_code, is_premium) values ('$user_id', '$user_name', '$password', '$user_email', '$admin_user', '$user_tl', '$is_premium')";

            //Execute SQL and check for errors
            if (!mysqli_query ($conn, $query)) {
              echo ("SQL Error: ") . $query . "<br>" . mysqli_error($conn);
            } else {

              //New account has been created, now copy categories to user version.
              $sql_copy_cat = "INSERT INTO `tb_user_categories` (`user_id`, `category_id`, `category_desc`) SELECT ?, category_id, category_desc FROM `tb_categories`";
              $run_copy_cat = $conn->prepare($sql_copy_cat);

              $run_copy_cat->bind_param("i", $user_id );

              if ($run_copy_cat->execute()) {
                  //echo ("Categories duplicated.");
              } else {
                  echo "Error duplicating categories (tb_user_categories)" . $run_copy_cat->error;
              }

              $run_copy_cat->close();




                            //New account has been created, categories have been copied. Now add row to tb_steaks.
                            $sql_set_target = "INSERT INTO `tb_streaks` (`user_id`, `max_test_streak`, `max_word_streak`) VALUES (?, ?, ?)";
                            $run_set_target = $conn->prepare($sql_set_target);
              
                            $run_set_target->bind_param("sii", $user_id, $max_test_streak, $max_word_streak);
              
                            if ($run_set_target->execute()) {
                                //echo ("Categories duplicated.");
                            } else {
                                echo "Error setting targets" . $run_set_target->error;
                            }
                            
                            $run_set_target->close();


              //success, so redirect the user
              header("Location: login.php?account-created");
              die;            
            }
        } else //if all above is true
        {
            echo "Please enter some valid information.";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylin.css">
    <title>Word Up: Sign Up</title>

    <style>

    </style>
</head>
<body style="background-color: rgb(176, 240, 207)">
<p>&nbsp;</p>
  
  <!-- Code to display alert to the user. -->
  <?php 
    if(isset($_GET['wrong-format-username'])){ 
  ?>
  <div class="alert error">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    Error. An incorrectly formatted username was entered. Use only charcters [a-z] or [0-9].
  </div>
  <?php } ?>

  <!-- Code to display alert to the user. -->
  <?php 
    if(isset($_GET['username-exists'])){ 
  ?>
    <div class="alert error">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Error. An account already exists with this username.
    </div>
  <?php } ?>
    
    <!-- Start of the code for the form. -->
  <div class="container">
    <!-- code here -->
    <div class="card">
        <h2 style="text-align: center; font-family: 'Montserrat', sans-serif; padding-top:10px;">
          SIGN UP FOR AN ACCOUNT
          <!--<small>Let us create your account</small> -->
        </h2>
      <form class="card-form" method="post">
        <div class="alternate-input">
          <input type="text" class="alternate-input-field" name="user_name" required/>
          <label class="alternate-input-label">User Name</label>
        </div>
        <div class="alternate-input">
          <input type="email" class="alternate-input-field" name="user_email" required/>
          <label class="alternate-input-label">E-MAIL</label>
        </div>
        <div class="alternate-input">
          <input type="text" class="alternate-input-field" name="password" required/>
          <label class="alternate-input-label">Password</label>
        </div>
        <div class="alternate-button-wrap">
          <button class="alternate-button" name="SignUp" type="submit">SIGN UP</button>
        </div>
      </form>
      <div class="card-info">
        <p>Have an account already? <a href="login.php">Login</a></p>
      </div> 
    </div>
  </div>

</body>
</html>