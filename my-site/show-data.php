<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This page can show the user the words that they have entered into the database. The user can choose to view the words by 
    category or all at once. There is also a button to allow the user to enter more words. 

    DETAILS:
    There is a DB call to tb_categories to populate the category drop-down menu. 
    Leaving the cateory drop-down blank will return all words from all categories. 
    There are a couple of counters on the screen, 1) count of all words for the user 2) count of words per category. The count for the number
    of words is found by the calling of the 'count_records' function. 
    The table will display a word per row. In each row, there will be an option to edit and delete words.
    The edit option will direct the user to the 'edit-record.php' file. 

    CHANGE HISTORY:

    02-03-25: Added clarification to select SQL for users word list, to sort by 'date' descending, showing newest words first.

    02-03-25: Mastery Changes. Added new column to table, 'Mastered'. Clicking on the icon will update tb_vocab to set word to Y or N in the 
              'is_mastered' column. The icon will change accordingly. 

              Added new checkbox on the form. If ticked, it will hide 'mastered' words from the list. Unfortunately, the styles for the
              checkbox are in the head of this file and not in the .css file.


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
	
  $cnt = 0; //define variable.
	$user_data = check_login($conn); //if logged in, this variable will contain the user data
  $rowcount = count_records($cnt);

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



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Word Up: Your Words</title>
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
  <p></p>

    <!-- Give the user some additional stats if they want to see them. . -->
    <details>
      <summary>Stats for <?php echo $user_data['user_name']; ?>.</summary>
        <p>Total number of words: <?php echo $rowcount; ?> <strong>|</strong> Number of categories used: <?php 
            
            //declare variable
            $category_count = 0;

            $num_categories = count_categories($category_count);
            echo $num_categories ?>
        </p>

        <ul>
          <?php 
            //set user_id so we can only see which words the logged in user has entered.
            $user_id = $_SESSION['user_id'];

            //php code to select from db
            $new_sql = "SELECT `category_desc`, COUNT(*) AS cat_count FROM `tb_vocab` WHERE user_id='$user_id' GROUP BY category_desc";
            $new_run = mysqli_query($conn, $new_sql);

            //Check for errors on sql query
            if (!$new_run) {
              echo "Error: " . mysqli_error($conn);
            } elseif (mysqli_num_rows($new_run) > 0) {
              //echo "Select successful, found " . mysqli_num_rows($result) . " rows.";
            } else {
              echo "There is a problem finding category count.";
            }

            while ($new_row = $new_run->fetch_assoc()) {    
          ?>
            <li> <i><?php echo $new_row["cat_count"] ?></i> words in the <i><b><?php echo $new_row["category_desc"] ?></b></i> category.</li>
          <?php
            } //while ends here
          ?>
        </ul>
    </details>

  <div class="card">
    <!-- Dropdown to select category -->
    <div class="custom-select">
      <form method="post">
        <label for="category-label" class="new-input-label">Choose a category. <span style="font-size: 10px;">(Leave blank to see all.)</span></label>
          <select name="category" id="category" class="new-input-field">
            <option value="">-- Category --</option>    
              <?php
                  if ($result->num_rows > 0 ) {
                    while ($row = $result->fetch_assoc()) {
                      echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
                    }
                  }
              ?>
          </select>
          <p></p>
          <label class="container" style="font-family: 'Rubik', arial, sans-serif;">Hide Mastered Words
        <input type="checkbox" id="hide" name="hide" value="Hide">
      <span class="checkmark"></span></label>

          <p></p>
        <input style="width:100%;" type="submit" value="FILTER WORDS" class="btn btn--secondary">
      </form>
    </div>
  </div>

  <p></p>

    
  <!-- Add button for adding vocab 
  <a href="input.php" class="button">Add Vocab</a> -->


  <!-- Notification of successful update. -->
  <?php 
    if(isset($_GET['record-updated'])){ 
  ?>
    <div class="alert success">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Success: Your changes have been saved.
    </div>
  <?php } ?>

  <!-- Notification of successful deletion.  -->
  <?php 
    if(isset($_GET['record-delete'])){ 
  ?>
    <div class="alert warning">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Warning. A record has been deleted.
    </div>
  <?php } ?>

  <!-- Notification of unsuccessful deletion.  -->
  <?php 
    if(isset($_GET['record-not-deleted'])){ 
  ?>
    <div class="alert failure">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Warning. A record failed to delete.
    </div>
  <?php } ?>

  <!-- Notification of successful mastery.  -->
  <?php 
    if(isset($_GET['record-mastered'])){ 
  ?>
    <div class="alert success">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Success: Your changes have been saved.
    </div>
  <?php } ?>

  <!-- Notification of unsuccessful mastery.  -->
  <?php 
  if(isset($_GET['record-not-mastered'])){ 
  ?>
    <div class="alert failure">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Warning. This update failed.
    </div>
  <?php } ?>



  <?php
      //Get value from drop-down
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $category_desc = $_POST['category'];
      }
  ?>

  <table class="hoverTable">
    <thead>
      <tr>
        <td>TARGET LANGUAGE</td>
        <td>NATIVE LANGUAGE</td>
        <td>Edit</td>
        <td>Delete</td>
        <td>Mastered</td>
      </tr>
    </thead>
    <tbody>

    <?php 
        //set user_id so we can only see which words the logged in user has entered.
        $user_id = $_SESSION['user_id'];


        /*
          Logic below will decide which SQL statement to query the DB. There are a few scenarios:
          1. On page load, so no category chosen or checkbox ticked.
          2. Page has been loaded, user has selected no category and ticked the checkbox.
          3. Page has been loaded, user has choosen a cateogry but not ticked the checkbox.
          4. Page has been loaded, user has choosen a category and ticked the checkbox.
        */
        if (empty($category_desc) && (isset($_POST['hide']))) {
            $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and is_mastered='N' ORDER BY date DESC";
          } elseif (empty($category_desc) && (!isset($_POST['hide']))) {
              $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY date DESC";
        } elseif (!empty($category_desc) && (isset($_POST['hide']))) {
            $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and category_desc='$category_desc' and is_mastered='N' ORDER BY date DESC";
            //Call function to count rows for a category.
            $category_count = count_records_by_category($category_desc);
        } else {
            $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and category_desc='$category_desc' ORDER BY date DESC";
            //Call function to count rows for a category.
            $category_count = count_records_by_category($category_desc);
        }



        $run = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_array($run)){
    ?>
    <tr>
        <td><?php echo $row['fr_text'] ?></td>
        <td><?php echo $row['en_text'] ?></td>
        <td><a href="edit-record.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a></td>
        <td><a href="delete-record.php?id=<?php echo $row['id']; ?>"><i class="fa-regular fa-trash-can align-center" style="color: #ec4e32;"></i></a></td>
        <td>
          <a href="master-record.php?id=<?php echo $row['id']; ?>&is_mastered=<?php echo $row['is_mastered']; ?>"> <!-- Pass variables in URL -->
            <?php if ($row['is_mastered'] === 'Y') { ?>
              <i class="fa-regular fa-circle-check" style="color: #b0f0cf;"></i>
            <?php } else { ?>
              <i class="fa-regular fa-circle-xmark" style="color: #fb0909;"></i></i>
            <?php } ?>
          </a>
        </td>
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
