<?php

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

include("include/connection.php");
include("include/functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Something was posted
    //collect the data from the POST variable
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    $string = $user_name;
    
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
    <link rel="stylesheet" href="css/advanced.css">
    <title>Login</title>

    <style>
    .alert {
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 4px;
  font-size: 16px;
  font-weight: bold;
}

.success {
  color: #155724;
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
}

.warning {
  color: #856404;
  background-color: #fff3cd;
  border: 1px solid #ffeeba;
}

.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}

.error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
</style>
</head>
<body>

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
    
<div class="container">
	<!-- code here -->
	<div class="card">
		<div class="card-image">	
			<h2 class="card-heading">
                <?php
                         if(isset($_GET['account-created'])){ 
                ?>
				Login to your new account.
                <?php } elseif(isset($_GET['wrong-format-username'])) { ?>
                    Try again.
                    <?php } else { ?>
                    Login.
                    <?php } ?>

				<!--<small>Let us create your account</small> -->
			</h2>
		</div>
		<form class="card-form" method="post">
			<div class="input">
				<input type="text" class="input-field" name="user_name" required/>
				<label class="input-label">User Name</label>
			</div>
			<div class="input">
				<input type="password" class="input-field" name="password" required/>
				<label class="input-label">Password</label>
			</div>
			<div class="action">
				<button class="action-button" name="Login" type="submit">Login</button>
			</div>
		</form>
		<div class="card-info">
			<p>No account? <a href="signup.php">Sign Up</a></p>
		</div> 
	</div>
</div>

</body>
</html>