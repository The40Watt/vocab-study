<!-- 
	
	AUTHOR: STEPHEN LENNON
	DATE: 27-03-2025

	HIGHLEVEL DESCRIPTION: 
	This is the page the user will see when they click the link on the 'login.php' page when they have forgotten their password.

	DETAILS:
	This file is called from the 'login.php' page.
	There is a form presented, allowing the user to enter an e-mail address to receive the reset email link.
	When the user submits the e-mail address, the logic in 'send-password.php' will be executed.


	CHANGE HISTORY:


-->
  
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/stylin.css">
	<title>Word Up: Forgot Password</title>
</head>
<body style="background-color: rgb(176, 240, 207)">

	<!-- Code to display alert to the user. -->
	<?php 
	if(isset($_GET['email-sent'])){ 
	?>
	  <div class="alert success">
		<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
		An email to reset your password has been sent. Check your e-mail inbox. 
	  </div>
	<?php } ?>

	<p>&nbsp;</p>
	<div class="container">
		<div class="card">
			<h2 style="text-align: center; font-family: 'Montserrat', sans-serif; padding-top:10px;">
				FORGOT PASSWORD
			</h2>
			<form class="card-form" method="post" action="send-password-reset.php">
			<div class="alternate-input">
				<input type="email" class="alternate-input-field" name="email" id="email" required/>
				<label for="email" class="alternate-input-label">E-MAIL</label>
			</div>
			<div class="alternate-button-wrap">
				<button class="alternate-button" name="reset" type="submit">SUBMIT</button>
			</div>
			</form>
		</div>
	</div>
		

	
</body>
</html>