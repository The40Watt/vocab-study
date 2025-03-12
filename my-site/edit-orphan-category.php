<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 09-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file presents a screen to the user that allows them to move the 'orphaned' word to a new category. The user
    can save or cancel their changes.

    DETAILS:
    The input field that holds the orphaned word is read-only, there is another screen for updating words.
    The DB processing to make the category change happens on 'update-orphaned-category.php'.

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

$result = populate_category_dropdown();


?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Word Up: Orphaned Words</title>
		<link rel="stylesheet" href="css/stylin.css">
	</head>

	<body>
		<header>
    		<?php include "include/nav.php" ?>
		</header>

	<main>

            <p>&nbsp;</p>
            <p>&nbsp;</p>

        <div class="card">
            
            <!-- Find the category using 'id' and 'user_id'. -->
            <?php           
                $id = $_GET['id'];
                $sql = "SELECT * FROM tb_vocab WHERE id='$id' and user_id='$user_id'";
                $run = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($run);
            ?>



		    <form action="update-orphaned-category.php" method="post" class="form-card">
                <!-- Adding hidden field to provide information for sql update. -->
                <input type="text" value="<?php echo $id; ?>" name="row_id" hidden>
                <div class="input">
                    <input type="text" name="fr_text" class="new-input-field" value="<?php echo $row['fr_text']; ?>" readonly>
                    <label class="new-input-label">Orphaned Word</label>
                </div>
                <p></p>
                <label for="category-label" class="new-input-label">Choose a new Category.</label>
                <select name="category" id="category" class="new-input-field" autofocus>
                <option value="">-- Category --</option>    
                    <?php
                        if ($result->num_rows > 0 ) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
                        }
                        }
                    ?>
                </select>
                <div class="action">
                    <!-- <input type="submit" name="SubmitButton" class="btn btn-secondary"> -->
                    <button style="width:100%;" class="btn btn--secondary" name="SubmitButton" type="submit">MAKE CHANGE</button><p></p>
                    <button style="width:100%;" class="btn" name="CancelButton" type="submit">CANCEL</button>
                </div>
		    </form> 
        </div>

        <?php
        //Get value from drop-down
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selected_category = $_POST['category'];
            echo ("help me");
        }

        ?>

	</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>