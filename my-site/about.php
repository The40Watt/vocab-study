
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

	21-03-25: Changed style of form over to 'alternate' style.

	06-04-25: Live fix. Adding text to allow users to contact me directly via email.


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

		<div class="main-section">
			<div class="page-title">
				<h1>GET IN TOUCH</h1>
			</div>
		</div>
			<!-- Code for the message form. -->
			<div class="card">
			<form method="POST" action="contact-form.php" class="card-form">
				<div class="alternate-input">
					<textarea class="alternate-input-field" name="message" id="message" tabindex="4" required></textarea>
					<label for="message" class="alternate-input-label">Message: </label>
					<input class="alternate-input-field" name="subject" type="text" id="subject" tabindex="3" required/>
					<label for="subject" class="alternate-input-label">Subject: </label>
					<input class="alternate-input-field" name="email" type="email" id="email" tabindex="2" required/>
					<label for="email" class="alternate-input-label">Email: </label>
					<input class="alternate-input-field" name="name" type="text" id="name" tabindex="1" required/>
					<label for="name" class="alternate-input-label">Name: </label>
				</div>
				<div class="alternate-button-wrap">
					<button style="width:100%;" class="alternate-button" type="submit">SEND MESSAGE</button>
				</div>
			</form>
			</div>
			<div style="text-align: center;">
				<h3>OR</h3>
				<p>Contact us directly at <a href="mailto:admin@wordup.ie">admin@wordup.ie</a>.</p>
			</div>
		</main>
		
		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>