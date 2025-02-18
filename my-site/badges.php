<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

    $rowcount = count_records($cnt); //call function to count words on tb_vocab
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/simple.css">
    <link rel="stylesheet" href="css/badge_style.css">
    <style>


    </style>
</head>
<body>
<header>
	<?php include "include/nav.php" ?>
		<h1><?php echo $user_data['user_name']; ?>'s Badges</h1>
</header>
<main>
    <p></p>
    <div class="main_container">
        <!-- This is the 1st badge. Awarded for creating an account. -->
        <div class="product">
            <img src="images/new_account.png" alt="">
            <div class="product_desc">
                <h3>Signed-Up</h3>
                <h6>Awarded: <?php 
                   $signedup_datetime = date_of_signup($date); //Call function to get date of signup
                
                   //Date in wrong format, change it below.
                   //$newDate = new DateTime($datetimeString); 
                   //$formatted_signedup_date = $newDate->format('d-m-Y');             
                   //echo $formatted_signedup_date; 

                   echo $signedup_datetime;
                   ?>
               </h6>
            </div>
        </div>
        <!-- This is the 2nd badge. Awarded for 1st word added. A function will also retrieve date first word created. -->
        <div class="product">
            <?php if($rowcount > 0 ) { 
                $date = find_date_first_word($date);
                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
            ?>
            <img src="images/badge_first_word.png" alt="">
            <div class="product_desc">
                <h3>1st word</h3>
                <h6>Awarded: <?php echo $formatted_firstword_date ?></h6>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 3rd badge. Awarded for using all 9 of the categories. -->
        <div class="product">
            <?php 
                
                $num_categories = count_categories($category_count);

                if ($num_categories == 9) { ?>            
            <img src="images/badge_first_word.png" alt="">
            <div class="product_desc">
                <h3>A word in every category.</h3>
                <h6>Awarded: </h6>
            </div>
            <?php } ?>
        </div>
        <div class="product">
            <img src="images/badge_first_word.png" alt="">
            <div class="product_desc">
                <h3>Some text here</h3>
            </div>
        </div>
        <div class="product">
            <img src="images/badge_first_word.png" alt="">
            <div class="product_desc">
                <h3>Some text here</h3>
            </div>
        </div>
    </div>  
</main>


    
</body>
</html>