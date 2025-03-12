<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 08-03-2025

    HIGHLEVEL DESCRIPTION: 
    Displays a list of the categories a user has.

    DETAILS:
    This is called form 'user-preferences.php'.
    It provides a way to edit and delete categories. 

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
	
  $cnt = 0; //define variable.
	$user_data = check_login($conn); //if logged in, this variable will contain the user data
  $rowcount = count_records($cnt);

?>


<?php
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Word Up: Your Categories</title>
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
      Success: Your category has been updated.
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
        <td>CATEGORY</td>
        <td>Edit</td>
        <td>Delete</td>
      </tr>
    </thead>
    <tbody>

    <?php 

    //Get the list of the users categories.   
    $result = populate_category_dropdown();


    // this is result from category dropdown
    while($row = mysqli_fetch_array($result)){
    ?>
    <tr>
        <td><?php echo $row['category_desc'] ?></td>
        <td><a href="edit-category.php?id=<?php echo $row['id']; ?>&category_desc=<?php echo $row['category_desc']; ?>"><i class="fa fa-edit"></i></a></td>
        <td><a href="delete-category.php?id=<?php echo $row['id']; ?>&category_desc=<?php echo $row['category_desc']; ?>"><i class="fa-regular fa-trash-can align-center" style="color: #ec4e32;"></i></a></td>
    </tr>
    <?php

    } //while ends here

    ?>

    </tbody>
  </table>
  <p></p></br>
</main>  


  <!-- Add the footer. -->
  <?php include "include/footer.php" ?>
</body>
</html>
