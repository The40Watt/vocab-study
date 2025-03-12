<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 08-03-2025

    HIGHLEVEL DESCRIPTION: 
    This will display a page with a table of words that have had their category deleted. This table will display an 
    edit icon in one of the columns allowing the user to move the word to a new category.

    DETAILS:
    This file is called from 'user-preferences.php'.
    The SQL will retrieve words from 'tb_vocab' where the category is set to 'DELETED'. The output of the SQL is feed
    into the table. 


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
  include("include/badge-record-functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>


<?php
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Word Up: Orphaned Words</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="css/stylin.css">
</head>

<style>
    .container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 18px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color: #37596f;
  font-family: "Rubik", sans-serif;
  font-weight: 700;

}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  box-shadow: 2px 2px 0 0 black;

}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;

}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);

}

</style>

<body>

<header>
  <?php include "include/nav.php" ?>
</header>
<main>




  <!-- Notification of successful update. -->
  <?php 
    if(isset($_GET['category-updated'])){ 
  ?>
    <div class="alert success">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Success: The words category has been updated.
    </div>
  <?php } ?>

  <!-- Notification of successful deletion.  -->
  <?php 
    if(isset($_GET['category-deleted'])){ 
  ?>
    <div class="alert warning">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Warning. A category has been deleted.
    </div>
  <?php } ?>

  <!-- Notification of unsuccessful deletion.  -->
  <?php 
    if(isset($_GET['category-not-deleted'])){ 
  ?>
    <div class="alert failure">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Warning. Category failed to delete.
    </div>
  <?php } ?>

  <p>&nbsp;</p>
  <p>&nbsp;</p>

  <table class="hoverTable">
    <thead>
      <tr>
        <td>WORD(s) with no category</td>
        <td>Edit</td>
      </tr>
    </thead>
    <tbody>

    <?php 
        $ORPHAN = "DELETED";

        $sql_find_orphans = "SELECT `id`, `fr_text`, `en_text`, `user_id` FROM `tb_vocab` WHERE user_id=? and category_desc = ?";
        
        $run_find_orphans = $conn->prepare($sql_find_orphans);

        //Bind parameters
        $run_find_orphans->bind_param("is", $user_id, $ORPHAN);

        //Execute SQL
        $run_find_orphans->execute();
            
        //Get the result
        $sql_result = $run_find_orphans->get_result();
        
        if($sql_result->num_rows > 0) {
            while ($row = $sql_result->fetch_assoc()) {

    ?>
    <tr>
        <td><?php echo $row['fr_text']; ?></td>
        <td><a href="edit-orphan-category.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a></td>
       <!-- <td><a href="delete-category.php?id=<?php echo $row['id']; ?>"><i class="fa-regular fa-trash-can align-center" style="color: #ec4e32;"></i></a></td> -->
    </tr>
    <?php
            } //end while
        } else {
            echo ("<h2>No orphan records found. All words have a category. </h2>");

        }
        
        $run_find_orphans->close();
        $conn->close();
    ?>

    </tbody>
  </table>
  <p></p></br>
</main>  


  <!-- Add the footer. -->
  <?php include "include/footer.php" ?>
</body>
</html>
