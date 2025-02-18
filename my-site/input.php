

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>

<?php

	//Get data from ct_categories
	$sql = "SELECT * FROM `ct_categories`";
	$result = $conn->query($sql);

	echo $sql;

?>

<?php


//Connection to db is open, code in connections.php
if(isset($_POST['SubmitButton']))
{


	//get user_id to distingush which user is inputting data.
    $user_id = $_SESSION['user_id'];

	//Below is original input code. Replaced with 'binding' parameters to make it more secure. 
	/*
	$sql = "INSERT INTO `tb_vocab`(`fr_text`, `en_text`, `user_id`, `test_count`) VALUES ('$french','$english','$user_id','0')";
	echo $sql;
	$run = mysqli_query($conn, $sql);
	
	if($run) {
		echo "The data was inserted successfully.";
		header("Location: show-data.php");
	} else {
		echo "Error: The data was not inserted.";
	}
	
	*/

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
	
	if($stmt) {
		echo "The data was inserted successfully.";
		header("Location: show-data.php");
	} else {
		echo "Error: The data was not inserted.";
	}
}

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Stephen's Homepage</title>
		<link rel="stylesheet" href="css/simple.css">
		<link rel="stylesheet" href="css/advanced.css">

	</head>

	
	
	<body>


		<header>

	
		<?php include "include/nav.php" ?>

			<h1><?php echo $user_data['user_name']; ?>'s Add Vocabulary Page</h1>
		</header>

		<main>


<!--
		<form action="input.php" method="post"> -->
		<!--
		If I want the page to stay on the same page after inputting the data to the database, I need to comment out the line above that uses the 'action' attribute and un comment the line below. 
		<form method="post">
		-->
 <!--       French: <input type="text" name="fr_text">
        <br><br>
        English: <input type="text" name="en_text">
        <br><br>
        <input type="submit" name="SubmitButton">
		

    </form>
-->





<div class="container">
	<!-- code here -->
	<div class="card">
		<div class="card-image">	
			<h2 class="card-heading">
				Add new words.
				<!--<small>Let us create your account</small> -->
			</h2>
		</div>
		<form class="card-form" action="input.php" method="POST">
		<div class="input">
			<label class="input-label">Choose</label>
				<select id="category" class="input-field" name="category">

					<option value=""></option>
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
				<input type="text" class="input-field" name="fr_text" required/>
				<label class="input-label">French text</label>
			</div>
			<div class="input">
				<input type="text" class="input-field" name="en_text" required/>
				<label class="input-label">English text</label>
			</div>
			<div class="action">
				<button class="action-button" name="SubmitButton" type="submit">Add Vocabulary</button>
			</div>
		</form>
		<!--<div class="card-info">
			<p>By signing up you are agreeing to our <a href="#">Terms and Conditions</a></p>
		</div> -->
	</div>
</div>

		</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
	</body>
</html>