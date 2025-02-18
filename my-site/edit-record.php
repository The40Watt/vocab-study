<?php

//Connection variables. 

/*$server = "localhost";
$dbuser = "root";
$password = "";
$database_name = "french";

$conn = mysqli_connect($server, $dbuser, $password, $database_name);*/




//Put user_id into session and check on each page to see if the user_id is legit.
session_start();

//$_SESSION;

include("include/connection.php");
include("include/functions.php");

$user_data = check_login($conn); //if logged in, this variable will contain the user data


?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Stephen's Edit Page</title>
		<link rel="stylesheet" href="css/simple.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


        <style>
            body{
    
            }
            .form-container {
                max-width: 600px
            }
            </style>

	</head>

	
	
	<body>


		<header>

	
		<?php include "include/nav.php" ?>

			<h1> Stephen's Edit page</h1>
		</header>

		<main>


        <div class="form-container">

            <?php
            
                $id = $_GET['id'];
                $sql = "SELECT * FROM tb_vocab WHERE id='$id'";
                $run = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($run);

             ?>

		<form action="update-record.php" method="post">

        <!-- Adding hidden field to provide information for sql update. -->
         <input type="text" value="<?php echo $id; ?>" name="row_id" hidden>
        
        French: <input type="text" name="fr_text" class="form-control" value="<?php echo $row['fr_text']; ?>">
        <br><br>
        English: <input type="text" name="en_text" class="form-control" value="<?php echo $row['en_text']; ?>">
        <br><br>
        <input type="submit" name="SubmitButton" class="btn btn-primary">
		

    </form> 
</div>

		</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
	</body>
</html>