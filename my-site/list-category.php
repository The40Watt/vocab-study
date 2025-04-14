<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 08-03-2025

    HIGHLEVEL DESCRIPTION: 
    Displays a list of the categories a user has.

    DETAILS:
    This is called form 'user-preferences.php'.
    It provides a way to edit and delete categories. 

    CHANGE HISTORY:

    22-03-25:   Changing look to alternate look. Adding title.

                Fixed 'Confirm Form Resubmission' error. Using the PRG (Post/Redirect/Get) pattern.


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


</style>

<body>

<header>
  <?php include "include/nav.php" ?>
</header>
<main>

<p>&nbsp;</p>
  <div class="main-section">
        <div class="page-title">
            <h1>YOUR CATEGORIES</h1>
        </div>
  </div>


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

    //Adding this if... statement below to fix 'confirm resubmission error'.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Process the form data
      $name = $_POST['name'] ?? ''; 

      // Redirect to prevent form resubmission
      header("Location: ".$_SERVER['PHP_SELF']);
      exit();
    }

    //Get the list of the users categories.   
    $result = populate_category_dropdown();


    // this is result from category dropdown
    while($row = mysqli_fetch_array($result)){
    ?>
    <tr>
        <td><?php echo $row['category_desc'] ?></td>
        <td><a href="edit-category.php?id=<?php echo $row['id']; ?>&category_desc=<?php echo $row['category_desc']; ?>"><i class="fa fa-edit fa-lg"></i></a></td>
        <td><a href="delete-category.php?id=<?php echo $row['id']; ?>&category_desc=<?php echo $row['category_desc']; ?>"><i class="fa-regular fa-trash-can align-center fa-lg" style="color: #ec4e32;"></i></a></td>
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
