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
            //save to database

            $user_id = random_num(20); //create a random user_id

            $query = "insert into tb_users (user_id, user_name, password) values ('$user_id', '$user_name', '$password')";

            mysqli_query($conn, $query);

            //redirect the user
           header("Location: login.php?account-created");

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
    <link rel="stylesheet" href="css/advanced.css">
    <title>Sign Up</title>

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
    
<div class="container">
	<!-- code here -->
	<div class="card">
		<div class="card-image">	
			<h2 class="card-heading">
				Sign-up for an account
				<!--<small>Let us create your account</small> -->
			</h2>
		</div>
		<form class="card-form" method="post">
			<div class="input">
				<input type="text" class="input-field" name="user_name" required/>
				<label class="input-label">User Name</label>
			</div>
			<div class="input">
				<input type="text" class="input-field" name="password" required/>
				<label class="input-label">Password</label>
			</div>
			<div class="action">
				<button class="action-button" name="SignUp" type="submit">Sign Up</button>
                <!-- <input type="submit" value="SignUp" /> -->
			</div>
		</form>
		<div class="card-info">
			<p>Have an account already? <a href="login.php">Login</a></p>
		</div> 
	</div>
</div>

</body>
</html>