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

            Adding extra security feature - password hash. The hash is irreversible, so it cannot be converted back to the original password.

  30-03-25: Added check for duplicate email, so a person cannot sign-up for an account if the email address already exists on tb_users.

  01-04-25: Live fix. Setting 'type' of password input field to be 'password'.

  04-04-25: Live fix. Added additional text to describe format of acceptable password.

  05-04-25: New test case sensitivity feature. Need to populate 'tb_user_pref' on account creation. Default is case sensitivity off.
  
  */

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	  include("include/connection.php");
    include("include/functions.php");
    include("include/error-logging.php");

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

        //Check for special character like !@#$%^&*
        if (!preg_match('/[^\w]/', $password)) {
          header("Location: signup.php?password-needs-special");
          die;
        }

        //Additional validation on password length.
        if (strlen($password) < 8) {
            header("Location: signup.php?password-too-short");
            die;
        } elseif (strlen($password) > 20) {
            header("Location: signup.php?password-too-long");
            die;
        } else {
            //echo "Password is valid.";
        }


        //Check username exists already
        if (!empty($user_name)) {
          //read from database, get user.
          $query = "select * from tb_users where user_name = '$user_name'";

          $run = mysqli_query($conn, $query);

          $username_row = mysqli_num_rows($run);

          if ($username_row > 0) {
            echo "The user name already exists";
            header("Location: signup.php?username-exists");
            die;
          }
        }

        //Check if email already exists in DB
        if (!empty($user_email)) {
          //read from database
          $query_email = "select * from tb_users where email = '$user_email'";

          $run_email_query = mysqli_query($conn, $query_email);

          $useremail_row = mysqli_num_rows($run_email_query);

          if ($useremail_row > 0) {
            $run_email_query -> close();
            echo ("This email already exists.");
            header("Location: signup.php?email-exists");
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

            //Need to hash password for safety.
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "insert into tb_users (user_id, user_name, password, email, admin_user, tl_code, is_premium) values ('$user_id', '$user_name', '$hashed_password', '$user_email', '$admin_user', '$user_tl', '$is_premium')";

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


                //New account has been created, categories have been copied. Now add row to tb_user_pref.
                $default_case = 'N';
                $sql_set_case = "INSERT INTO `tb_user_pref` (`user_id`, `is_case_sensitive`) VALUES (?, ?)";
                $run_set_case = $conn->prepare($sql_set_case);
  
                $run_set_case->bind_param("is", $user_id, $default_case);
  
                if ($run_set_case->execute()) {
                    //echo ("Categories duplicated.");
                } else {
                    echo "Error setting case sensitivity" . $run_set_case->error;
                }
                
                $run_set_case->close();


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

  <!-- Code to display alert to the user. -->
  <?php 
  if(isset($_GET['email-exists'])){ 
  ?>
    <div class="alert error">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Error. An account already exists with this e-mail.
    </div>
  <?php } ?>

  <!-- Code to display alert to the user, password too short. -->
  <?php 
    if(isset($_GET['password-too-short'])){ 
  ?>
  <div class="alert error">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    Error. Password must be at least 8 characters long. 
  </div>
  <?php } ?>

  <!-- Code to display alert to the user, password too long. -->
  <?php 
    if(isset($_GET['password-too-long'])){ 
  ?>
  <div class="alert error">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    Error. Password must be no longer than 20 characters.
  </div>
  <?php } ?>

  <!-- Code to display alert to the user, password too long. -->
  <?php 
    if(isset($_GET['password-needs-special'])){ 
  ?>
  <div class="alert error">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
    Error. Password must contain at least one special character.
  </div>
  <?php } ?>
    
  <div class="login-outer">
    <div class="login-inner-1">
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
            <input type="password" class="alternate-input-field" name="password" required/>
            <label class="alternate-input-label">Password</label>
          </div>
          <div class="card-info">
            <p><strong>User name</strong> must contain only alphanumeric characters, i.e. [a-z] [0-9]</p>
            <p><strong>Password</strong> must be between 8 and 20 characters and contain a special character.</p>
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
  </div>


</body>
</html>