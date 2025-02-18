<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data
  $rowcount = count_records($cnt);
?>


<?php

	//-------------------
  // Function to select categories from the codes table. 
  //-------------------
	$sql = "SELECT * FROM `ct_categories`";
	$result = $conn->query($sql);

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/simple.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

  <style>
    .alert {
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 4px;
  font-size: 16px;
  font-weight: bold;
}

.success {
  color: #155724;
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
}

.warning {
  color: #856404;
  background-color: #fff3cd;
  border: 1px solid #ffeeba;
}

.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}

table {
	border-collapse: collapse;
    font-family: Tahoma, Geneva, sans-serif;
}
table td {
	padding: 15px;
}
table thead td {
	background-color:rgb(6, 64, 136);
	color: #ffffff;
	font-weight: bold;
	font-size: 13px;
	border: 1px solid #54585d;
}

table tbody td {
	color: #636363;
	border: 1px solid #dddfe1;
}
table tbody tr {
	background-color: #f9fafb;
}
table tbody tr:nth-child(odd) {
	background-color: #ffffff;
}

.hoverTable tr:hover {
	background-color:rgb(99, 183, 134);
}

.hoverTable thead td {
	background-color:rgb(6, 64, 136);
	color: #ffffff;
	font-weight: bold;
	font-size: 13px;
	border: 1px solid #54585d;
}

.custom-select {
            min-width: 350px;
            position: relative;
            }

            .custom-select select {
            appearance: none;
            width: 50%;
            font-size: 1.15rem;
            padding: 0.675em 6em 0.675em 1em;
            background-color: #fff;
            border: 1px solid #caced1;
            border-radius: 0.25rem;
            color: #000;
            cursor: pointer;
            }

</style>
</head>
<body>

<header>
  <?php include "include/nav.php" ?>
  <h1><?php echo $user_data['user_name']; ?>'s Vocabulary List</h1>
</header>

<p></p>
<!-- Dropdown to select category -->
<div class="custom-select">
            <form method="post">
                <label for="category-label" >Choose a Category of words to view. (Leave blank to view all.)</label>
                    <select name="category" id="category">
                        <option value="">-- Category --</option>    
                        <?php
                            if ($result->num_rows > 0 ) {
                              while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
                              }
                            }
                        ?>
                    </select>
                    <input type="submit" value="Submit">
            </form>
   </div>



<div class="jumbotron text-center">
  <p>Vocab can be added and removed using this application. !</p> 
</div>
  
<!-- Add button for adding vocab -->
<a href="input.php" class="button">Add Vocab</a>



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

 <h1><?php echo $user_data['user_name']; ?>, you have <?php echo $rowcount; ?> records in total.</h1>

        <?php
            //Get value from drop-down
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $category_desc = $_POST['category'];
               // echo $category_desc;
            }
          ?>

      <table class="hoverTable">
        <thead>
          <tr>
            <td>French Text</td>
            <td>English Text</td>
            <td>Edit</td>
            <td>Delete</td>
         </tr>
        </thead>
        <tbody>

        <?php 
            //set user_id so we can only see which words the logged in user has entered.
            $user_id = $_SESSION['user_id'];

            //php code to select from db
            //if category is not set, then bring all words back for the user.
            if (empty($category_desc)) {
                $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id'";
            } else {
                $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and category_desc='$category_desc'";

                //Call function to count rows for a category.
                $category_count = count_records_by_category($category_desc);
                ?>
                <h3>And you have <?php echo $category_count; ?> records in the <?php echo $category_desc; ?> category.</h3>

              <?php
            }

         //   $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and category_desc='$category_desc'";
            $run = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_array($run)){

        ?>

        <tr>
            <td><?php echo $row['fr_text'] ?></td>
            <td><?php echo $row['en_text'] ?></td>
            <td><a href="edit-record.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a></td>
            <td><a href="delete-record.php?id=<?php echo $row['id']; ?>"><i class="fa-regular fa-trash-can align-center text-danger"></i></a></td>
        </tr>

        <?php

        } //while ends here

        ?>

        </tbody>
    </table>
    

</body>
</html>
