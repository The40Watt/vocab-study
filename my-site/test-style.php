<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/advanced.css">
    <link rel="stylesheet" href="css/simple.css">
    <title>Testing Styles</title>
</head>
<body>

<header>
<?php include "include/nav.php" ?>

<h1> Stephen's style page</h1>


</header>

<main>

<form action="" method="post">

        French: <input type="text" name="fr_text">
        <br><br>
        English: <input type="text" name="en_text">
        <br><br>
        <input type="submit" name="SubmitButton">
		

</form>

    <div class="container">
	<!-- code here -->
	<div class="card">
		<div class="card-image">	
			<h2 class="card-heading">
				Add new words.
				<!--<small>Let us create your account</small> -->
			</h2>
		</div>
		<form class="card-form" action="" method="post">
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
    
</body>
</html>