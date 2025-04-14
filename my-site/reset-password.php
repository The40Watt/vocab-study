<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 27-03-2025

    HIGHLEVEL DESCRIPTION: 
    This is the page the user will see when they click the link from the reset password email. 

    DETAILS:
    This file is called from the reset password email. 
    The token is retrieved from the URL and used to find the user details on 'tb_users'.
    It will present a form with two passwords fields. The two passwords must match. Submission will call the "process-reset-password.php" file. 


    CHANGE HISTORY:


-->

<?php

    //Open DB connection
    include("include/connection.php");
    include("include/error-logging.php");


    $token = $_GET["token"];

    $token_hash = hash("sha256", $token);

    $sql = "SELECT * FROM  tb_users WHERE reset_token_hash=?";


    $run = $conn->prepare($sql);

    $run->bind_param("s", $token_hash);

    $run->execute();

    $result = $run->get_result();

    $user = $result->fetch_assoc();

    if ($user == null) {
        die ("Token not found.");
    }

    if (strtotime($user["reset_token_expires_at"]) <= time()) {
        die ("Token has expired.");
    }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/stylin.css">
    <title>Word Up: Reset Password</title>
</head>
<body style="background-color: rgb(176, 240, 207)">
    
    <!-- Code to display alert to the user. -->
    <?php 
    if(isset($_GET['passwords-do-not-match'])){ 
    ?>
      <div class="alert error">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        The two passwords did not match. 
      </div>
    <?php } ?>


<p>&nbsp;</p>
    <div class="container">
        <div class="card">
            <h2 style="text-align: center; font-family: 'Montserrat', sans-serif; padding-top:10px;">
                RESET PASSWORD
            </h2>
            <form class="card-form" method="post" action="process-reset-password.php">
            <div class="alternate-input">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="password" class="alternate-input-field" name="password_confirmation" id="password_confirmation" required/>
                <label for="password_confirmation" class="alternate-input-label">Confirm Password</label>
                <input type="password" class="alternate-input-field" name="password" id="password" required/>
                <label for="password" class="alternate-input-label">New Password</label>
            </div>
            <div class="alternate-button-wrap">
                <button class="alternate-button" name="reset" type="submit">SEND</button>
            </div>
            </form>
        </div>
    </div>

</body>
</html>