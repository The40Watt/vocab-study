<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This page allows the user to login into their account. 

    DETAILS:
    This file mainly consists of a simple form. 
    The form is styled using 'advanced.css'. 
    Changing colour of the background in the <body> tag.


    CHANGE HISTORY:

    22-03-25: Changed look to alternate style.

    24-03-25: I added email to the table 'tb_users'. This means the user might want to login using either user_name or email. The form has 1 field for both formats. So two checks
              have been added to validate whether the value in $user_name is an email or not. Depending on whether it is or not, a check for non-alphanumeric characters will be 
              done and the SQL will need to be different.

              In the signup.php file, I added code to hash the password. Below I added to code to validate the hashed password when logging in.

    27-03-25: Adding link to new 'Forgot Passord' page.

    31-03-25: Added new div classes around the content to make this page responsive. 

-->

<?php

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

include("include/connection.php");
include("include/functions.php");
include("include/error-logging.php");


if($_SERVER['REQUEST_METHOD'] == "POST")
{

    //collect the data from the POST variable
    $user_name = $_POST['user_name'];
    $password = trim($_POST['password']);

    //Check is user_name is an email. If it is not, then validate for non-alphanumeric characters
    if (!filter_var($user_name, FILTER_VALIDATE_EMAIL)) {

      $string = $user_name;
      
      //Check that user hasn't entered any non-alphanumeric characters
      if (!preg_match('/^[a-zA-Z0-9]+$/', $string)) {
    
          echo "The string contains non-alphanumeric characters.";
          header("Location: login.php?wrong-format-username");
          die;
      }
    }

    //if username and password not empty
    if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
    {

        //Adding check to see if the value in $user_name is an email address or simple user_name
        if (filter_var($user_name, FILTER_VALIDATE_EMAIL)) {
          $query = "select * from tb_users where email = '$user_name' limit 1";
        } else {
          $query = "select * from tb_users where user_name = '$user_name' limit 1";
        }

        //read from database, get user.
        //$query = "select * from tb_users where user_name = '$user_name' and password = '$password' limit 1";

        $result = mysqli_query($conn, $query);

        if($result)
        {
            if($result && mysqli_num_rows($result) > 0) //check have at least 1 result
            {
                $user_data = mysqli_fetch_assoc($result);

                //Added code here to validate the hash password
                if (password_verify($password, $user_data['password']))
                {
                    //setting the users user_id to the global variable that is checked on each page in the check_login()
                    $_SESSION['user_id'] = $user_data['user_id'];
                    //redirect the user
                    header("Location: index.php");
                    die;
                }
            }
        }
        echo "You have entered incorrect details.";
                      //success, so redirect the user
                      header("Location: login.php?incorrect-details");
                      die;    

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
    <link rel="stylesheet" href="css/stylin-login.css">
    <title>Word Up: Login</title>

    <style>

      </style>
</head>
<body style="background-color: rgb(176, 240, 207)">

<!-- Code to display alert to the user. -->
<?php 
     if(isset($_GET['account-created'])){ 
    ?>
    <div class="alert success">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Success. Your new account has been created. Please login in below.
    </div>
    <?php } ?>

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
    if(isset($_GET['incorrect-details'])){ 
    ?>
      <div class="alert error">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Error. You have entered an incorrect User Name or Password.
      </div>
    <?php } ?>





<div class="login-outer">
  <div class="login-inner-1">
    <h1>Welcome to Word <span class="high">Up</span></h1>
    <h3>Your Personalised Vocabulary Library!</h2>

    <p>
      Learning a new language is exciting but keeping track of new words can be challenging. That’s where Word <span class="high">Up</span> comes in. This platform can help you curate your own vocabulary library, tailored to your learning needs.</p>
    <p>
      Whether it’s a <i>cupla focal</i> in Irish, or <i>quelque mots</i> en français, Word <span class="high">Up</span> can handle it all. Add words, organise, and review them anytime.
    </p>
    <p>
      But learning isn’t just about collecting words – it’s about remembering them when you need it. To that end, test your memory with an interactive quiz designed to reinforce your knowledge and boosting retention. Whether you’re a beginner or advanced learner, this tool will help you master your target language, <strong>one word at a time</strong>.
    </p>
    <p>
      Start building out your vocabulary library today.
    </p>

    <p>
      <strong>FEATURES:</strong>
    </p>

      <ul>
        <li>Simple, bloat free learning experience</li>
        <li>Personally curated vocabulary library</li>
        <li>Focus on privacy, no cookies, no newsletters, etc...</li>
        <li>Track your achievements</li>
        <li>Testing your weakest words</li>
        <li>Suggested focus areas</li>
      </ul>
    </div>
    <div class="login-inner-2">
        <div class="card">
          <div >	
            <h2 style="text-align: center;">
              <?php
                if(isset($_GET['account-created'])){ 
              ?>
                NEW ACCOUNT READY, SIGN-IN
              <?php } elseif(isset($_GET['wrong-format-username'])) { ?>
                TRY AGAIN
              <?php } else { ?>
                SIGN-IN
              <?php } ?>
            </h2>
          </div>
            <form class="card-form" method="post">
              <div class="alternate-input">
                <input type="text" class="alternate-input-field" name="user_name" required/>
                <label class="alternate-input-label">User Name or E-mail</label>
              </div>
              <div class="alternate-input">
                <input type="password" class="alternate-input-field" name="password" required/>
                <label class="alternate-input-label">Password</label>
              </div>
              <div class="alternate-button-wrap">
                <button class="alternate-button" name="Login" type="submit">LOGIN</button>
              </div>
            </form>
          <div class="card-info">
            <p>No account yet? <a href="signup.php">Sign Up</a></p>
            <p><a href="forgot-password.php">Forgot Password</a></p>
          </div> 
        </div>
    </div>  <!-- close login-inner-2 -->
  </div> <!-- close login-outer -->
 

  
</body>
</html>