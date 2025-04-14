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

    03-03-25:   Mastery changes. Added new toggle field to allow the user to switch the word between mastered or not. The logic has been updated
                in 'update-record.php' file to make the update to tb_vocab.

    21-03-25:   Changing style over to alternate.   

    31-03-25:   Live fix. The UTF-8 encoding is not working correclty so some characters not displaying correctly. I've added a line just before the DB query
                is run to try to enforce UTF-8.


-->


<?php

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

//$_SESSION;

include("include/connection.php");
include("include/functions.php");
include("include/error-logging.php");


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

            <p>&nbsp;</p>
            <p>&nbsp;</p>

        <div class="card">
            
            <!-- Find the word on tb_vocab using 'id'. -->
            <?php           
                $id = $_GET['id'];
                $sql = "SELECT * FROM tb_vocab WHERE id='$id'";
                
                //Trying to enforce UTF-8
                mysqli_set_charset($conn, "utf8mb4");

                $run = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($run);
                $is_toggled = $row['is_mastered'];
            ?>

		    <form action="update-record.php" method="post" class="form-card">
                <!-- Adding hidden field to provide information for sql update. -->
                <input type="text" value="<?php echo $id; ?>" name="row_id" hidden>
                <div class="input">
                    <input type="text" name="fr_text" class="alternate-input-field" value="<?php echo $row['fr_text']; ?>">
                    <label class="alternate-input-label">Native Language</label>
                </div>
                <div class="input">
                    <input type="text" name="en_text" class="alternate-input-field" value="<?php echo $row['en_text']; ?>">
                    <label class="alternate-input-label">Target Language</label>
                </div>
                <!-- <div class="checkbox_item citem_3">
		            <div class="title">Word Mastered?</div>
		                <label class="checkbox_wrap">
			                <input type="checkbox" name="toggle" class="checkbox_inp" value="Y" <?php echo ($is_toggled === 'Y') ? 'checked' : ''; ?>>
		                	<span class="checkbox_mark"></span>
		                </label>
	            </div> -->

                <div class="switch-sudo-label">
                <p>WORD MASTERED?</p>
                </div>
                <div class="master-switch">
                    <input id="switch-1" type="checkbox" class="master-switch-input" name="toggle" class="checkbox_inp" value="Y" <?php echo ($is_toggled === 'Y') ? 'checked' : ''; ?>>
                    <label for="switch-1" class="master-switch-label"></label>
                </div>  
                <div class="alternate-button-wrap">
                    <button class="alternate-button" name="SubmitButton" type="submit">SAVE</button>
                    <button class="alternate-button-cancel" name="CancelButton" type="submit">CANCEL</button>
                </div>
		    </form> 
        </div>

	</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>