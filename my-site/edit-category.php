<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 09-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file displays a screen to the user to allow them to edit categories. They can save, or cancel their changes.

    DETAILS:
    Called from 'list-categories.php'. 
    The actual SQL processing to update the DB is in 'update-category.php'.


    CHANGE HISTORY:



-->


<?php

//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

//$_SESSION;
$user_id = $_SESSION['user_id'];

include("include/connection.php");
include("include/functions.php");
include("include/error-logging.php");


$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Word Up: Edit Categories</title>
		<link rel="stylesheet" href="css/stylin.css">
	</head>

	<body>
		<header>
    		<?php include "include/nav.php" ?>
		</header>

	<main>

            <p>&nbsp;</p>
            <div class="main-section">
                <div class="page-title">
                    <h1>EDIT DESCRIPTION</h1>
                </div>
            </div>
            <p>&nbsp;</p>

        <div class="card">
            
            <!-- Find the category using 'id' and 'user_id'. -->
            <?php           
                $id = $_GET['id'];

                //Trying to enforce UTF-8
                $conn->set_charset("utf8mb4");

                $sql = "SELECT * FROM tb_user_categories WHERE id='$id' and user_id='$user_id'";
                $run = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($run);
            ?>

		    <form action="update-category.php" method="post" class="form-card">
                <!-- Adding hidden field to provide information for sql update. -->
                <input type="text" value="<?php echo $id; ?>" name="row_id" hidden>
                <div class="alternate-input">
                    <input id="category_desc" type="text" name="category_desc" class="alternate-input-field" value="<?php echo $row['category_desc']; ?>">
                    <label for="category_desc" class="alternate-input-label">Edit Category</label>
                </div>
                <div class="alternate-button-wrap">
                    <!-- <input type="submit" name="SubmitButton" class="btn btn-secondary"> -->
                    <button class="alternate-button" name="SubmitButton" type="submit">MAKE CHANGE</button><p></p>
                    <button class="alternate-button-cancel" name="CancelButton" type="submit">CANCEL</button>
                </div>
		    </form> 
        </div>

        

	</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>