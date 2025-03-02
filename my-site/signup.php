<?php

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

            //Admin_user is a mandatory field to fill.
            $admin_user = 'N';

            $query = "insert into tb_users (user_id, user_name, password, admin_user) values ('$user_id', '$user_name', '$password', '$admin_user')";

            //Execute SQL and check for errors
            if (!mysqli_query ($conn, $query)) {
              echo ("SQL Error: ") . $query . "<br>" . mysqli_error($conn);
            } else {
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
        <div class="input">
          <input type="text" class="new-input-field" name="user_name" required/>
          <label class="new-input-label">User Name</label>
        </div>
        <div class="input">
          <input type="text" class="new-input-field" name="password" required/>
          <label class="new-input-label">Password</label>
        </div>
        <div class="action">
          <button style="width:100%" class="btn btn--secondary" name="SignUp" type="submit">SIGN UP</button>
        </div>
      </form>
      <div class="card-info">
        <p>Have an account already? <a href="login.php">Login</a></p>
      </div> 
    </div>
  </div>

</body>
</html>