<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This file is the screen and logic to edit a word on tb_vocab.

    DETAILS:
    This file is called from 'show-data.php' when the user presses on the edit icon.
    It will return the user to 'show-data.php' with a message to say that message has been edited.
    Need to keep the link scripts below os that the 'hidden' attribute works as expected.
    Added a Cancel button to bring user back to 'show-data.php' page with no changes saved. 


    CHANGE HISTORY:


-->


<?php

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

//$_SESSION;

include("include/connection.php");
include("include/functions.php");

//this will ensure PHP displays all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Word Up: Edit Words</title>
		<link rel="stylesheet" href="css/stylin.css">
       <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

	</head>

	<body>
		<header>
    		<?php include "include/nav.php" ?>
		</header>

	<main>

        <div class="card">
            
            <!-- Find the word on tb_vocab using 'id'. -->
            <?php           
                $id = $_GET['id'];
                $sql = "SELECT * FROM tb_vocab WHERE id='$id'";
                $run = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($run);
            ?>

		    <form action="update-record.php" method="post" class="form-card">
                <!-- Adding hidden field to provide information for sql update. -->
                <input type="text" value="<?php echo $id; ?>" name="row_id" hidden>
                <div class="input">
                    <input type="text" name="fr_text" class="new-input-field" value="<?php echo $row['fr_text']; ?>">
                    <label class="new-input-label">Native Language</label>
                </div>
                <div class="input">
                    <input type="text" name="en_text" class="new-input-field" value="<?php echo $row['en_text']; ?>">
                    <label class="new-input-label">Target Language</label>
                </div>
                <div class="action">
                    <!-- <input type="submit" name="SubmitButton" class="btn btn-secondary"> -->
                    <button style="width:100%;" class="btn btn--secondary" name="SubmitButton" type="submit">MAKE CHANGE</button><p></p>
                    <button style="width:100%;" class="btn" name="CancelButton" type="submit">CANCEL</button>
                </div>
		    </form> 
        </div>

	</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>