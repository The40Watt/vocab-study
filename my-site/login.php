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


-->

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

    //collect the data from the POST variable
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    $string = $user_name;
    
    //Check that user hasn't entered any non-alphnumeric characters
    if (!preg_match('/^[a-zA-Z0-9]+$/', $string)) {
  
        echo "The string contains non-alphanumeric characters.";
        header("Location: login.php?wrong-format-username");
        die;
    }

    //if username and password not empty
    if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
    {
        //read from database, get user.
        $query = "select * from tb_users where user_name = '$user_name' and password = '$password' limit 1";

        $result = mysqli_query($conn, $query);

        if($result)
        {
            if($result && mysqli_num_rows($result) > 0) //check have at least 1 result
            {
                $user_data = mysqli_fetch_assoc($result);

                if($user_data['password'] === $password)
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
    * {
        box-sizing: border-box;
      }

      /* Create two equal columns that floats next to each other */
      .column {
        float: left;
        width:50%;
        padding: 10px;
        padding-top: 100px;
        padding-left: 100px;
        height: 850px; /* Should be removed. Only for demonstration */
      }

      .column > h1 {
        letter-spacing: 2px;
        padding-left:100px;
      }

      .column >p,
      .column > ul {
    padding-left: 100px;
  }

      .high {
        position: relative;
        top: -7px;
      }

      .column2 {
        float: left;
        width:40%;
        padding: 10px;
        padding-top: 100px;
        padding-left: 10px;
        height: 850px; /* Should be removed. Only for demonstration */
      }

      /* Clear floats after the columns */
      .row:after {
        content: "";
        display: table;
        clear: both;
      }

      /* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
      @media screen and (max-width: 600px) {
        .column {
          width: 100%;
        }
      }
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

<div class="row">
  <div class="column">
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
<p>&nbsp;</p>
<p>
  <strong>FEATURES:</strong>
</p>

  <ul>
    <li>Simple, bloat free learning experience</li>
    <li>Personally curated vocabulary library</li>
    <li>Complete privacy, no cookies, no e-mails, etc...</li>
    <li>Track your achievements</li>
    <li>Testing your weakest words</li>
  </ul>
  <p>&nbsp;</p>

    </div> <!-- end column -->

<div class="column">
    <p>&nbsp;</p>
    <p>&nbsp;</p>

    <!-- Start of code here for the form. -->    
    <div class="container">
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
            <div class="input">
              <input type="text" class="new-input-field" name="user_name" required/>
              <label class="new-input-label">User Name</label>
            </div>
            <div class="input">
              <input type="password" class="new-input-field" name="password" required/>
              <label class="new-input-label">Password</label>
            </div>
            <div class="action">
              <button class="btn btn--secondary" name="Login" type="submit">LOGIN</button>
            </div>
          </form>
        <div class="card-info">
          <p>No account yet? <a href="signup.php">Sign Up</a></p>
        </div> 
      </div>
    </div>
            </div> <!-- end column -->
            </div> <!-- end row -->
</body>
</html>