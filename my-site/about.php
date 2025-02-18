<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Contact</title>
		<link rel="stylesheet" href="css/simple.css">
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

				.card {
				margin: 2rem auto;
				display: flex;
				flex-direction: column;
				width: 100%;
				max-width: 425px;
				background-color: #FFF;
				border-radius: 10px;
				box-shadow: 0 10px 20px 0 rgba(#999, .25);
				padding: .75rem;
			}
		</style>
	</head>

	
	
	<body>

		<header>

		<?php include "include/nav.php" ?>
            
			<h1>Contact</h1>
		</header>

		<!-- Code to display alert to the user. -->
		<?php 
			if(isset($_GET['contact-email-sent'])){ 
		?>
			<div class="alert success">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
				Success. Your message has been sent.
			</div>
		<?php } ?>

		<!-- Code to display alert to the user. -->
		<?php 
			if(isset($_GET['contact-email-fail'])){ 
		?>
			<div class="alert warning">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
				Warning. Your message has not been sent.
			</div>
		<?php } ?>

		
		<main>
			<div class="card">
			<form method="POST" action="contact-form.php" >
				<h2 >Contact</h2>
				<p><label>Name: </label><input name="name" type="text" id="name" required/></p>
				<p><label>Email: </label><input name="email" type="email" id="email" required/></p>
				<p><label>Subject: </label><input name="subject" type="text" id="subject" required/></p>
				<p></label>Message: </label><textarea name="message" id="message" required></textarea></p>
				<p><input type="submit" value="Send" /></p>
			</form>
			</div>

		</main>
		
		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
	</body>
</html>