<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This is the page to allow the user to input new words in their target langugage and the translation. 

    DETAILS:
	This file mainly consists of a simple form. 
	The form is styled using 'advanced.css'. 
	The drop-down form for categories is driven by the table tb_categories.

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

<?php

	//Get data from ct_categories, used to populate teh drop-down form. 
	$sql = "SELECT * FROM `ct_categories`";
	//$result = $conn->query($sql);
	$result = mysqli_query($conn, $sql);

	//Check for errors on sql query
	if (!$result) {
		echo "Error: " . mysqli_error($conn);
	} elseif (mysqli_num_rows($result) > 0) {
		//echo "Select successful, found " . mysqli_num_rows($result) . " rows.";
	} else {
		echo "There is a problem finding the list of categories.";
	}
?>

<?php


//Connection to db is open, code in connections.php
if(isset($_POST['SubmitButton']))
{

	//get user_id to distingush which user is inputting data.
    $user_id = $_SESSION['user_id'];


	//Initialise variables for inserting new word
	$french = $_POST['fr_text'];
	$english = $_POST['en_text'];
	$category_desc = $_POST['category'];
	$initialCount = 0;

	//prepare SQL statement
	$stmt = $conn->prepare("INSERT INTO `tb_vocab` (`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc` ) VALUES (?, ?, ?, ?, ?)");
	

	//bind parameters, tell DB what parameters are.
	$stmt->bind_param("ssiis", $french, $english, $user_id, $initialCount, $category_desc);
	$stmt->execute();
	$stmt->close();
	
	//Keep user on the same page so they can enter more words, rather than redirect to show-data.php
	if($stmt) {
		header("Location: input.php?data-entered");
	} else {
		echo "Error: The data was not inserted.";
	}
}

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Word Up: Add a Word</title>
		<!-- <link rel="stylesheet" href="css/simple.css">
		<link rel="stylesheet" href="css/advanced.css"> -->
		<link rel="stylesheet" href="css/stylin.css">
	</head>
	
	<body>
		<header>
			<?php include "include/nav.php" ?>
		</header>

		<main>

			<!-- Notification of successful data input. -->
			<?php 
				if(isset($_GET['data-entered'])){ 
			?>
			<div class="alert success">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
				Success: Word added to database. Add another?
			</div>
			<?php } ?>
			
			<!-- Start of code for the input form. -->
			<div class="container">
				<div class="card">
					<!--<div class="card-image">	
						<h2 class="card-heading">
							Add new words.
						</h2>
					</div>-->
					<form class="card-form" action="input.php" method="POST">
						<div class="input">
							<label class="new-input-label"></label>
								<select id="category" class="new-input-field" name="category" required>
									<option value="">-- Category --</option>
									<?php
										if ($result->num_rows > 0 ) {
											while ($row = $result->fetch_assoc()) {
												echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
											}
										}
									?>
								</select>
						</div>
						<div class="input">
							<input type="text" class="new-input-field" name="fr_text" required/>
							<label class="new-input-label">Target Language</label>
						</div>
						<div class="input">
							<input type="text" class="new-input-field" name="en_text" required/>
							<label class="new-input-label">Native Language</label>
						</div>
						<div class="action">
							<button style="width:100%;" class="btn btn--secondary" name="SubmitButton" type="submit">ADD WORD</button>
						</div>
					</form>
				</div>
			</div>

		</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
	</body>
</html>