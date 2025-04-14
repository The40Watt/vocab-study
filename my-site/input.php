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
	
	02-03-25:	 Added new column to insert SQL (is_mastered).

	06-03-25:	Added new code to cater for changes to how badges for word count are calculated. After a word is input,
				a count is done of words on tb_vocab. If it hits a milestone number, it will add a row to tb_badge_record
				if it doesn't exist. 

	08-03-25:	Changed SQL that was returning the list of categories in the drop-down menu. It is now populated by a function called 'populate_category_dropdown'.
	
	04-04-25:   Live fix. The UTF-8 encoding is not working correclty so some characters not displaying correctly. I've added a line just before the DB query
				is run to try to enforce UTF-8.

-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	include("include/error-logging.php");
    include("include/badge-record-functions.php");

	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>

<?php
	//Populate the array to fill the category drop-down.
	$result = populate_category_dropdown();
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
	$is_mastered = 'N';
	$currentDateTime = date("Y-m-d H:i:s");

	//Trying to enforce UTF-8
	$conn->set_charset("utf8mb4");


	//prepare SQL statement
	$stmt = $conn->prepare("INSERT INTO `tb_vocab` (`fr_text`, `en_text`, `user_id`, `test_count`, `category_desc`,`is_mastered`,`date_mastered` ) VALUES (?, ?, ?, ?, ?, ?, ?)");
	

	//bind parameters, tell DB what parameters are.
	$stmt->bind_param("ssiisss", $french, $english, $user_id, $initialCount, $category_desc, $is_mastered, $currentDateTime);
	$stmt->execute();
	$stmt->close();
	

	if ($stmt) {
		//After insert of new word, check word count to see if a badge has been earned. 
		$sql_count_rows = "SELECT COUNT(*) AS count FROM `tb_vocab` WHERE user_id = ?";

		$run_count_row = $conn->prepare($sql_count_rows);

		$run_count_row->bind_param("i", $user_id);
		$run_count_row->execute();

		$run_count_row_result = $run_count_row->get_result();
		$row = $run_count_row_result->fetch_assoc();

		$word_count = $row['count'];

		//Checking word count related badges - #2, #5, #6, #7, #8
    	//Call new function to insert a row tb_badge_record when user hits milestone word count.
    	switch ($word_count) {
        case 1:
            update_word_badges_records($word_count);
            //echo ("this is 1");
            break;
        case 25:
            update_word_badges_records($word_count);
            //echo ("this is 25");
            break;
        case 100:
            update_word_badges_records($word_count);
            //echo ("this is 100");
            break;
        case 250:
            update_word_badges_records($word_count);
            //echo ("this is 250");
            break;
        case 1000:
            update_word_badges_records($word_count);
            //echo ("this is 1000");
            break;
    	}
	}

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
		<link rel="stylesheet" href="css/stylin.css">
	</head>
	
	<body>
		<header>
			<?php include "include/nav.php" ?>
		</header>

		<main>

		<div class="main-section">
			<div class="page-title">
				<h1>ADD A NEW WORD</h1>
			</div>
  		</div>
			<p>&nbsp;&nbsp;</p>
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
			<!-- <div class="container"> -->
			<div style="min-height: 600px">
				<div class="card">
					<!--<div class="card-image">	
						<h2 class="card-heading">
							Add new words.
						</h2>
					</div>-->
					<form class="card-form" action="input.php" method="POST" >
						<div class="alternate-input">
							<label class="alternate-input-label" for="category"></label>
								<select id="category" class="alternate-input-field" name="category" required autofocus>
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
						<div class="alternate-input">
							<input type="text" class="alternate-input-field" name="fr_text" id="tl_lang" required/>
							<label class="alternate-input-label" for="tl_lang" >Target Language</label>
						</div>
						<div class="alternate-input">
							<input type="text" class="alternate-input-field" name="en_text" id="native_lang" required/>
							<label class="alternate-input-label" for="native_lang">Native Language</label>
						</div>
						<div class="alternate-button-wrap">
							<button class="alternate-button" name="SubmitButton" type="submit">ADD WORD</button>
						</div>
					</form>
				</div>
			</div>
			<!-- </div> -->
			<p>&nbsp;&nbsp;</p>
		</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
	</body>
</html>