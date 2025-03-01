
<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 18-02-2025

    HIGHLEVEL DESCRIPTION: 
    This file just contains the form for a user to submit a contact message. 
	There are some checks to see if the message was submitted correctly and will display an alert, success or warning. 
	The messages are not sent via e-mail, they are stored in tb_message.

    DETAILS:
	There are some checks to see if the message was submitted correctly and will display an alert, success or warning. 
	The messages are not sent via e-mail, they are stored in tb_message.
	Contains link to awesome fonts icons. 

    CHANGE HISTORY:


-->
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
		<title>Word Up: Contact</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="css/stylin.css">
		<style>
		</style>
	</head>

	
	
	<body>

		<header>
			<?php include "include/nav.php" ?>
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
			<!-- Code for the message form. -->
			<div class="card">
			<form method="POST" action="contact-form.php" class="card-form">
				<h2>Get in touch <i class="fa-regular fa-comment fa-bounce"></i></h2>
				<p>
				<div class="input">
					<input class="new-input-field" name="name" type="text" id="name" required/>
					<label class="new-input-label">Name: </label>
				</div>
				<div class="input">
					<input class="new-input-field" name="email" type="email" id="email" required/>
					<label class="new-input-label">Email: </label>
				</div>
				<div class="input">
					<input class="new-input-field" name="subject" type="text" id="subject" required/>
					<label class="new-input-label">Subject: </label>
				</div>
				<div class="input">
					<textarea class="new-input-field" name="message" id="message" required></textarea>
					<label class="new-input-label">Message: </label>
				</div>
				<div class="action">
					<input style="width:100%;" class="btn btn--secondary" type="submit" value="SEND MESSAGE" />
				</div>
			</form>
			</div>

		</main>
		
		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>