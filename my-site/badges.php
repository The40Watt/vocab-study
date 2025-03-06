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

    05-03-25: Added badge #10 - user has mastered one word. It will call a new function (check_badge_record) to determine if user has
              earned badge and the date. 

              Bug fixes to remove unnecessary parameter variable that were causing warnings. 


-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
    include("include/error-logging.php");
    include("include/badge-record-functions.php");

    //Declare variables
    $cnt = 0;
	

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
                  
                    $signedup_datetime = date_of_signup(); //Call function to get date of signup
                    
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
                $date = find_date_first_word();
                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
            ?>
            <img src="images/badge_first_word.png" alt="">
            <div class="product_desc">
                <h3>1st word added.</h3>
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
                //$category_count = 0;
                //$num_categories = count_categories($category_count);

                $badge_number = 3;
                count_categories_for_badge($badge_number);

                list($all_categories, $all_categories_date) = check_badge_record($badge_number);


               // if ($num_categories == 9) { 
               if ($all_categories == 'Y') {
            ?>            
            <img src="images/all_categories.png" alt="">
            <div class="product_desc">
                <h3>All categories</h3>
                <h6>Awarded: 
                    <?php 

                        if (is_null($all_categories_date)){
                            echo ("");
                        } else {
                            //Change format of date
                            $formatted_var_one = new DateTime($all_categories_date);
                            $formatted_version = $formatted_var_one->format('d-m-Y');
                            echo $formatted_version;
                        }
                       
                       /*
                       $newest_category = date_last_category();
                    
                        //Change format of date.
                        $fomratted_datetime = new DateTime($newest_category);
                        $formattedDate = $fomratted_datetime->format('d-m-Y');
                        echo $formattedDate;
                        */
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

                $badge_number = 4;

                list($test_count, $first_test_date) = check_user_has_tested($badge_number);
                
                if($test_count > 0) { 
            ?>
            <img src="images/first_test.png" alt="">
            <div class="product_desc">
                <h3>Started a test.</h3>
                <h6>Awarded: 
                    <?php
                        if (is_null($first_test_date)) {
                            echo ("Date is null.");
                        } else {
                        //Change format of date.
                        $formatted_test_date = new DateTime($first_test_date);
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
            <?php 
                /*
                if($rowcount > 24 ) { 
                //$date = find_date_twentyfive_word($date); //Call function to get date of 25th word
                $date = find_date_milestone_word(25, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
                */

                $word_badge_num = 5;   //badge_num on table
                
                list($word_badge_five, $word_badge_five_date) = check_badge_record($word_badge_num);
                
                if($word_badge_five == 'Y') {  
            ?>
            <img src="images/badge_first_word.png" alt="">
            <div class="product_desc">
                <h3>25 words</h3>
                <h6>Awarded: 
                    <?php 

                    //echo $formatted_firstword_date
                    if (is_null($word_badge_five_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($word_badge_five_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
        <!-- This is the 6th badge. Awarded for 100 words. -->
        <div class="product">
            <?php 
                /*
                if($rowcount > 99 ) { 
                
                $date = find_date_milestone_word(100, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
                */
                $word_badge_num = 6;   //badge_num on table
                
                list($word_badge_six, $word_badge_six_date) = check_badge_record($word_badge_num);
                
                if($word_badge_six == 'Y') {  
            ?>
            <img src="images/100_words.png" alt="">
            <div class="product_desc">
                <h3>100 words</h3>
                <h6>Awarded: 
                    <?php 
                    
                    //echo $formatted_firstword_date 

                    if (is_null($word_badge_six_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($word_badge_six_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
        <!-- This is the 7th badge. Awarded for 250 words. -->
        <div class="product">
            <?php 
                /*
                if($rowcount > 249 ) { 
                $date = find_date_milestone_word(250, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
                */
                $word_badge_num = 7;   //badge_num on table
                
                list($word_badge_seven, $word_badge_seven_date) = check_badge_record($word_badge_num);
                
                if($word_badge_seven == 'Y') {
            ?>
            <img src="images/250_words.png" alt="">
            <div class="product_desc">
                <h3>250 words</h3>
                <h6>Awarded: 
                    <?php 
                    
                    //echo $formatted_firstword_date 
                    if (is_null($word_badge_seven_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($word_badge_seven_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
        <!-- This is the 8th badge. Awarded for 1000 words. -->
        <div class="product">
            <?php 
                /*
                if($rowcount > 999 ) { 
                $date = find_date_milestone_word(1000, $date); //Call function to get date of 100th word

                $newDate = new DateTime($date);
                $formatted_firstword_date = $newDate->format('d-m-Y');
                */
                $word_badge_num = 8;   //badge_num on table
                
                list($word_badge_eight, $word_badge_eight_date) = check_badge_record($word_badge_num);
                
                if($word_badge_eight == 'Y') {
            ?>
            <img src="images/1000_words.png" alt="">
            <div class="product_desc">
                <h3>1000 words</h3>
                <h6>Awarded: 
                    <?php 
                    
                    //echo $formatted_firstword_date 
                    if (is_null($word_badge_eight_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($word_badge_eight_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
        <!-- This is the 9th badge. Awarded for submiting a message. -->
        <div class="product">
            <?php 
                
              //  $has_contacted = did_reach_out($contacted, $date);
              $badge_number = 9;
              list($has_contacted, $message_date) = did_reach_out($badge_number);

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
        <!-- This is the 10th badge. Awarded for mastering a word. -->
        <div class="product">
            <?php
                $mastered_badge_num = 10;   //badge_num on table
                
                list($mastered_one, $mastered_one_date) = check_badge_record($mastered_badge_num);
                
                if($mastered_one == 'Y') {  
            ?>
            <img src="images/first_test.png" alt="">
            <div class="product_desc">
                <h3>Mastered 1 word.</h3>
                <h6>Awarded: 
                    <?php

                    if (is_null($mastered_one_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($mastered_one_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
         <!-- This is the 11th badge. Awarded for mastering a word. -->
         <div class="product">
            <?php
                $mastered_badge_num = 11;   //badge_num on table
                
                list($mastered_one, $mastered_one_date) = check_badge_record($mastered_badge_num);
                
                if($mastered_one == 'Y') {  
            ?>
            <img src="images/first_test.png" alt="">
            <div class="product_desc">
                <h3>Mastered 25 words.</h3>
                <h6>Awarded: 
                    <?php

                    if (is_null($mastered_one_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($mastered_one_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
        <!-- This is the 12th badge. Awarded for mastering a word. -->
        <div class="product">
            <?php
                $mastered_badge_num = 12;   //badge_num on table
                
                list($mastered_one, $mastered_one_date) = check_badge_record($mastered_badge_num);
                
                if($mastered_one == 'Y') {  
            ?>
            <img src="images/first_test.png" alt="">
            <div class="product_desc">
                <h3>Mastered 100 words.</h3>
                <h6>Awarded: 
                    <?php

                    if (is_null($mastered_one_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($mastered_one_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
        <!-- This is the 13th badge. Awarded for mastering a word. -->
        <div class="product">
            <?php
                $mastered_badge_num = 13;   //badge_num on table
                
                list($mastered_one, $mastered_one_date) = check_badge_record($mastered_badge_num);
                
                if($mastered_one == 'Y') {  
            ?>
            <img src="images/first_test.png" alt="">
            <div class="product_desc">
                <h3>Mastered 250 words.</h3>
                <h6>Awarded: 
                    <?php

                    if (is_null($mastered_one_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($mastered_one_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
                <!-- This is the 14th badge. Awarded for earning all other badges. -->
                <div class="product">
            <?php

                $badge_number = 14;
                $badge_completion = check_platinum_badge($badge_number);
                
                if($badge_completion >= 14) {  
            ?>
            <img src="images/first_test.png" alt="">
            <div class="product_desc">
                <h3>Earned all badges. SUPERSTAR.</h3>
                <h6>Awarded: 
                    <?php
                    
                    $badge_completion_date = check_platinum_badge_date($badge_number);
                    
                    if (is_null($badge_completion_date)){
                        echo ("");
                    } else {
                        //Change format of date
                        $formatted_var_one = new DateTime($badge_completion_date);
                        $formatted_version = $formatted_var_one->format('d-m-Y');
                        echo $formatted_version;
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
    </div>  
    <p></p>
</main>


<!-- Add the footer. -->
<?php include "include/footer.php" ?>
    
</body>
</html>