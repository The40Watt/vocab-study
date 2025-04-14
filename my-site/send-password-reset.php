<!-- 
	
	AUTHOR: STEPHEN LENNON
	DATE: 27-03-2025

	HIGHLEVEL DESCRIPTION: 
	This file is to generate the reset token and format the email.

	DETAILS:
	This file is called from 'forgot-password.php'.
	It will generate a random token and expiry time for the token. These values will be updated to 'tb_users' where the email matches the one on the form. 
	The text of the email is defined in this file. 


	CHANGE HISTORY:


-->

<?php

	//Open DB connection
	include("include/connection.php");
	include("include/error-logging.php");
	



	if(isset($_POST['email']))
	{
		$email = $_POST["email"];

		//Generate random token. Use bin2hex function to ensure it creates characters that can be used in a URL
		$token = bin2hex(random_bytes(16));

		//For security, create a hash value of the token
		$token_hash = hash("sha256", $token);

		//Create expiry value to prevent against brute force attacks, i.e. make the token expire and force user to get a new token
		$expiry = date("Y-m-d H:i:s", time() + 60 * 30);    //30 minutes

		//SQL to save token to DB
		$sql = "UPDATE `tb_users` SET `reset_token_hash`=?, `reset_token_expires_at`=? WHERE email=?";

		$run = $conn->prepare($sql);

		$run->bind_param("sss", $token_hash, $expiry, $email);

		$run->execute();


		if ($conn->affected_rows) {

			$mail = require __DIR__ . "/mailer.php";

			$mail->setFrom("noreply@wordup.ie");
			$mail->addAddress($email);
			$mail->Subject = "Word Up: Password Reset";
			$mail->Body = <<<END

			Click <a href="http://localhost/my-site/reset-password.php?token=$token">here</a> to reset your password.

			If you did not request your password to be reset, please ignore this message. 

			Word Up.

			END;

			try {
				$mail->send();
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
			}
		} 

		header("Location: forgot-password.php?email-sent");
		die;
		//Send this message anyway, even if email was not found. Prevents someone knowing what is a valid/invalid email.
		echo ("Message sent, please check your inbox.");
	}





