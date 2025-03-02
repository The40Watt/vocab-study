<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This file is for the Badges page in the website. Badges are effectively achievements/trophies for the user completing certain 
    tasks, e.g. input their first word, use all the available categories etc...

    DETAILS:
    9 awards. 
    The functions.php file is called frequently throughout this script to check for rows on tables, retrieve dates etc...


    CHANGE HISTORY:


-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
    include("include/error-logging.php");
	


	$user_data = check_login($conn); //if logged in, this variable will contain the user data

    $rowcount = count_records($cnt); //call function to count words on tb_vocab
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Up: Badges</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/stylin.css">
    <style>


    </style>
</head>
<body>
<header>
	<?php include "include/nav.php" ?>
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
                    
                    //Change format of date.
                    $fomratted_datetime = new DateTime($signedup_datetime);
                    $formattedDate = $fomratted_datetime->format('d-m-Y');
                    echo $formattedDate;
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
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 3rd badge. Awarded for using all 9 of the categories. -->
        <div class="product">
            <?php 
                //declare variable
                $category_count = 0;
                $num_categories = count_categories($category_count);

                if ($num_categories == 9) { ?>            
            <img src="images/all_categories.png" alt="">
            <div class="product_desc">
                <h3>All categories</h3>
                <h6>Awarded: 
                    <?php 
                        $newest_category = date_last_category($category_date);
                    
                        //Change format of date.
                        $fomratted_datetime = new DateTime($newest_category);
                        $formattedDate = $fomratted_datetime->format('d-m-Y');
                        echo $formattedDate;
                    ?>
                </h6>
            </div>
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 4th badge. Awarded for doing a test. -->
        <div class="product">
            <?php
                $test_count = check_tested($tested);
                
                if($test_count > 0) { ?>
            <img src="images/first_test.png" alt="">
            <div class="product_desc">
                <h3>Started a test.</h3>
                <h6>Awarded: 
                    <?php

                    $date_first_test = find_date_first_test($date);
                    //echo ("date from new function") . $date_first_test;
                    if (is_null($date_first_test)) {
                        echo ("Date is null.");
                    } else {
                    //Change format of date.
                    $formatted_test_date = new DateTime($date_first_test);
                    $formatted_test = $formatted_test_date->format('d-m-Y');
                    echo $formatted_test;
                    }
                    ?>
                </h6>
            </div>
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 5th badge. Awarded for 25 words. -->
        <div class="product">
            <?php if($rowcount > 24 ) { 
                //$date = find_date_twentyfive_word($date); //Call function to get date of 25th word
                $date = find_date_milestone_word(25, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
            ?>
            <img src="images/badge_first_word.png" alt="">
            <div class="product_desc">
                <h3>25 words</h3>
                <h6>Awarded: <?php echo $formatted_firstword_date ?></h6>
            </div>
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 6th badge. Awarded for 100 words. -->
        <div class="product">
            <?php if($rowcount > 99 ) { 
                $date = find_date_milestone_word(100, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
            ?>
            <img src="images/100_words.png" alt="">
            <div class="product_desc">
                <h3>100 words</h3>
                <h6>Awarded: <?php echo $formatted_firstword_date ?></h6>
            </div>
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 7th badge. Awarded for 250 words. -->
        <div class="product">
            <?php if($rowcount > 249 ) { 
                $date = find_date_milestone_word(250, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
            ?>
            <img src="images/250_words.png" alt="">
            <div class="product_desc">
                <h3>250 words</h3>
                <h6>Awarded: <?php echo $formatted_firstword_date ?></h6>
            </div>
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 8th badge. Awarded for 1000 words. -->
        <div class="product">
            <?php if($rowcount > 999 ) { 
                $date = find_date_milestone_word(1000, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
            ?>
            <img src="images/1000_words.png" alt="">
            <div class="product_desc">
                <h3>1000 words</h3>
                <h6>Awarded: <?php echo $formatted_firstword_date ?></h6>
            </div>
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
        <!-- This is the 9th badge. Awarded for submiting a message. -->
        <div class="product">
            <?php 
                
              //  $has_contacted = did_reach_out($contacted, $date);
              list($has_contacted, $message_date) = did_reach_out($contacted, $date);

                if ($has_contacted > 0) { ?>            
            <img src="images/submit_message.png" alt="">
            <div class="product_desc">
                <h3>You reached out!</h3>
                <h6>Awarded: <?php 
                    $newDate = new DateTime($message_date);
                    $formatted_message_date = $newDate->format('d-m-Y');
                    echo $formatted_message_date ?></h6>
            </div>
            <?php } else { ?>
                <img src="images/x.png" alt="">
            <div class="product_desc">
                <h3>Not earned yet.</h3>
            </div>
            <?php } ?>
        </div>
    </div>  
    <p></p>
</main>


<!-- Add the footer. -->
<?php include "include/footer.php" ?>
    
</body>
</html>