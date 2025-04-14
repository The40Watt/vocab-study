<!-- 
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This is the homepage for the whole site. It will have a different layout depending on a few factors. 

    DETAILS:
    The content of the page will be different depending on a number of factors;
      1. A brand new user, with no words added will see text telling them what they can do on the site. 
      2. A user who has words entered and taken tests will see some information related to the actions they've taken on the site previously.
      3. If the user is an admin, they will get a message to say they are admin. If there are new messages on the tb_message table, a notice will be display. An 
      admin user will have a button to mark messages as read. 


    CHANGE HISTORY:

    02-03-25:   Added style class 'recent' that will make lists dipslay horizontal. 
            
                Added change to recent activity related to mastery. A call to new function to retrieve last five mastered words. 

                 Added new function to calculate the percentage of mastered words out of the total number of words. 
    
    04-03-25:   Added new function to calculate the average time it takes to master a word. The output is display in the users stats.

                Fixed bug where the display of the page was broken when the user is new and has no words. Added multiple checks for rowcount (of words) greater 
                than zero.

    04-03-25:   Added new include script. Also calling new function (update_word_badges_records) to check badge records.

                Added call to new function (calc_overall_badge_completion) to calculate the percentage of badges a user has earned. This figure is feed into the script 
                to generate a third progress-bar. 

    05-03-25:   Added new function (update_mastery_badge_records). It will add row to tb_badge_record for a word mastery achievement if the row does not already exist.

    06-03-25:   Added two graphs - 1) line graph to display recent test scores, 2) doughnut graph to display usage of categories. Each graph
                uses a separate PHP file (graph-categories.php & graph-test-results.php) to gather the data for the graphs. The data is brought
                back into index.php in a JSON object.

    07-03-25:   Added two new stats - users longest streak at adding a word and users longest streak on record. Both are calculated in 'badge-record-functions.php'.

                Added two more stats - Users last test score by calling get_last_test_score() and users most tested category by calling find_most_test_category().

                Added 3 cross-site / cross-user trophies. 1) user with the most words, 2) user with the most tests 3) the first user to unlock all 14 badges. Trophy number
                3 once unlocked, is held by that user indefinitely. The other 2 trophies can switch between users. The calculations for each are in 'badge-record-functions.php'

    08-03-25:   Added new button to 'Quick Menu' - 'user preferences'.

    10-03-25:   Added new function call to find the users target language (find_users_tl). The result of this will be passed into the function to retrieve a random word from 
                one of the foreign dictionaries. Small change to text of the 'Random' word section to show which lanuage is being used. This variable is a session variable and
                is unset on logout.php.

                Added some logic around the greeting for the user. Now that we know the users TL, we can call a function (get_user_greeting) to get different greetings


-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();


    /**
     * COMMENTS FROM ABOVE CONTINUING HERE. THERE APPEARS TO BESOME CHARACTER LIMIT IN OPERATION. 
     * 
     * CHANGE HISTORY; 
     * 
     * 15-03-25:    Removed the 'Language Learning Tips' section and replaced with stats derived from users tests - tb_tests, tb_test_words 
     *              1. show users words they consistently score high and might want to mark as mastered
     *              2. show users words they consistently score low in and might want to test
     *              3. show users average word accuracy
     *              4. show users the category in which they have the highest accuracy
     *              5. show users the current days streak they are on for testing.  
     */

	$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
    include("include/file-functions.php");
    include("include/error-logging.php");
    include("include/badge-record-functions.php");
    include("include/test-stats-functions.php");
    include("include/admin-functions.php");
    include_once("include/constants.php");
    
	
    //Declare variables
    $cnt = 0;
    $word = '';
    $test_cnt = 0;
    $rand_word = '';
    $rowcount;
    $user_tl = '';
    $user_tl_upper = '';
    $num_registered_users = 0;
    $last_score = 0;
    $most_tested_category = '';
    $current_test_streak = 0;
    $total_tests = 0;
    $longest_streak = 0;
    $current_word_streak = 0;
    $max_test_streak = 0;
    $not_tested_count = 0;
    $mastered_count = 0;
    $tested_count = 0;
    $next_word = '';
    $num_registered_users = 0;
    $total_num_words_in_db = 0;
    $total_num_tests_in_db = 0;
    $is_a_site_champ = 'N';
    $is_premium = false;


    //Variable to define new user (no words on tbvocab)
    $new_user = '';

	$user_data = check_login($conn); //if logged in, this variable will contain the user data
	$rowcount = count_records($cnt); 
    $vocab_id_array =[];    //Used for auto marking words as mastered
    $vocab_id_low_scores_array = [];    //used for passing to test.php for hard test

    //Check if user is a premium user.
    $is_premium = is_user_premium();
    if ($is_premium === 'Y') { 
        $_SESSION['is_premium'] = true;
    } else {
        $_SESSION['is_premium'] = false; // Explicitly set for clarity
    }

    echo ("Is user premium: ") . $_SESSION['is_premium'];

    print_r($_SESSION['is_premium']);

    //The variables set below are used throughout the script. Only calling functions if user has at least one word.
    if ($rowcount > 0) {
        $next_word = next_word($word);
        $tested_count = number_of_tests($test_cnt); //find number of words tested
        $mastered_count = count_mastered_words();
        $not_tested_count = number_not_tested();
        $average_to_mastery = calc_average_time_mastery();
        list ($max_test_streak, $max_word_streak) = find_users_streak_targets();
        $current_word_streak = find_streak(); 
        $longest_streak = find_longest_streak(); 
        $total_tests = number_of_user_tests();



        //Preparation for word cloud
        //Call function to look for potential words to be marked as mastered
        $cloud_words = find_cloud_words();

        //Start preparation of data for word cloud. Move words from first array to new array.
        foreach ($cloud_words as $row) {
            $cloud_word[] = $row['fr_text'];
        }
        
        //Assign a weighting to each value.
        $wordData = array_map(function($word) {
            return [$word, rand(10, 50)]; // Random weight for demonstration
            //return [$word,15];
        }, $cloud_word);

        //Convert array to JSON to pass to wordcloud script.
        $jsonData = json_encode($wordData);

        //Checking values for site champions
        $is_a_site_champ = 'N';

        $am_i_word_leader = am_i_user_with_most_words();
        $am_i_tests_leader = am_i_user_with_most_tests();
        $badge_number = 14;
        $badge_completion = check_platinum_badge($badge_number);
        $am_i_platinum = am_i_platinum_user();

        //If user is gets a 'Y' on any of the 3 checks above, they are a site champion on something. 
        if ($am_i_word_leader == 'Y' || $am_i_tests_leader == 'Y' || $am_i_platinum == 'Y') {
            $is_a_site_champ = 'Y';
        }

    }

    //Variables below are used for a few different things but only worth calling the functions if user has done at least one test.
    if ($total_tests > 0) {
        $current_test_streak = find_test_streak();
        //$total_tests = number_of_user_tests();
        $category_stats = calc_category_highest_and_lowest_accuracy();
        $most_tested_category = find_most_test_category();
        $last_score = get_last_test_score();
        $potential_mastered = find_potential_mastered_words();

    }



    //If the random word for the session has already been set, then skip this call.
    if (!isset($_SESSION['session_word'])) {

        //Find users TL, pass it to function to get word of the day.
        $user_tl = find_users_tl();
        $_SESSION['session_tl'] = $user_tl;

        if ($user_tl !== 'XX') {
            $_SESSION['session_word'] = find_session_word($user_tl);
        }
    }

    //Run admin user functions to get stats
    if ($user_data['admin_user']) {
        $num_registered_users = find_number_registered_users();
        $total_num_words_in_db = find_total_words_in_db();
        $total_num_tests_in_db = find_total_tests_in_db();
    }

    //testing variable - REMOVE IT!!!
   // $row_count = 1;

    //Checking word count related badges - #2, #5, #6, #7, #8
    //Call new function to insert a row tb_badge_record when user hits milestone word count.
    /*
    switch ($row_count) {
        case 1:
            update_word_badges_records($row_count);
            echo ("this is 1");
            break;
        case 25:
            update_word_badges_records($row_count);
            echo ("this is 25");
            break;
        case 100:
            update_word_badges_records($row_count);
            echo ("this is 100");
            break;
        case 250:
            update_word_badges_records($row_count);
            echo ("this is 250");
            break;
        case 1000:
            update_word_badges_records($row_count);
            echo ("this is 1000");
            break;
    }
    */
    
    //testing variable - REMOVE IT!!!
    //$mastered_count = 1;
/*
    //Checking mastery related badges - #10, #11, #12, #13
    //Call new function to insert a row on tb_badge_record when user hits milestone of mastered words.
     switch ($mastered_count) {
        case 1:
            update_mastery_badge_records($mastered_count);
            echo ("this is 1");
            break;
        case 25:
            update_mastery_badge_records($mastered_count);
            echo ("this is 25");
            break;
        case 100:
            update_mastery_badge_records($mastered_count);
            echo ("this is 100");
            break;
        case 250:
            update_mastery_badge_records($mastered_count);
            echo ("this is 250");
            break;
    }
*/

    //Check for badge #3 - user has used all 9 categories.
    $badge_number = 3;
    count_categories_for_badge($badge_number);

    
?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta http-equiv="X-UA-Compatible" contents="IE=edge"> 
		<title>Word Up: Home</title>
		<link rel="stylesheet" href="css/index-stylin.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="scripts/wordcloud2.js"></script>
        <style>
            <style>
 #myProgress {
            width: 100%;
            background-color: #ddd;
        }




        #myBar {
            width: 1%;
            height: 30px;
            background-color: #04AA6D;
        }



        .recent {
            display: flex;
            gap: 10px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

       







.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    box-shadow: 5.9px 11.8px 11.8px hsl(0deg 0% 0% / 0.32);
    justify-content: center;
    align-items: center; 

}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 5.9px 11.8px 11.8px hsl(0deg 0% 0% / 0.32);
    width: 80%;
    position: relative; 

}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 18px;
    cursor: pointer;
}

/* Bigger Canvas */
 /* .modal canvas {
    width: 80% !important;
    height: 80% !important;
} */

canvas {
    cursor: pointer;
}












        </style>
	</head>

	<body>

    <header>
        <?php include "include/nav.php" ?>
    </header>
	<main>


    <!-- START OF NOTIFICATION MESSAGES. -->
    <!-- (1) Notification of successful words mastery update. -->
    <?php 
        if(isset($_GET['words-mastered'])){ 
    ?>
    <div class="alert success">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Success: Those words have been marked as 'Mastered'.
    </div>
    <?php } ?>

    <!-- (2) Notification of successful words mastery update. -->
    <?php 
        if(isset($_GET['words-not-mastered'])){ 
    ?>
    <div class="alert error">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Error. An issue occured marking those words as 'Mastered'.
    </div>
    <?php } ?>

    <!-- (3) Notification of successful update (Admin, marking messages as read.) -->
    <?php 
        if(isset($_GET['messages-read'])){ 
    ?>
    <div class="alert success">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Success: Messages marked as read.
    </div>
    <?php } ?>
    <!-- END OF NOTIFICATION MESSAGES. -->


    <!-- ############# START OF HEADING SECTION ################# -->
    <div class="main-section">
        <div class="page-title">
            <h1>WORD <span class="high">UP</span> DASHBOARD</h1>
        </div>
            


        <!-- ################## START OF INTRO TEXT SECTION ############## -->
         <!-- If $rowcount is 0, user has no vocab added. They get a different greeting. -->
        <div class="intro-text">
            <?php 
                //$rowcount = 0;	//Set to zero for testing.
                if($rowcount == 0) {      
            ?>
                <p> 
            <?php 
                //Calling function to retreive a greeting in users TL
                echo $user_greeting = get_user_greeting($_SESSION['session_tl']);
            ?>
                <b><?php echo $user_data['user_name']; ?></b>. Welcome to Word <span class="high">Up</span>.</p>
                <p>It seems that  you haven't added any words to your vocabulary list yet. Go to <i>Add a Word</i> now to get started.<br>
                Once you have added some words, you can check out your completed library of words in the <i>Your Words</i> page.<br>         
                When you are ready to test your recall on these words, you can do so on the <i>Test Now</i> page.</p>
                <p>By signing up, you have just earned your first <i>badge</i>. Check it out on the <i>Badges</i> page and earn more by using the site.</p>
                
            <?php	} else { //user has $rowcount > 0
            ?>
                <p> 
            <?php 
                //Calling function to retreive a greeting in users TL
                echo $user_greeting = get_user_greeting($_SESSION['session_tl']);
            ?>     
                <b><?php echo $user_data['user_name']; ?></b>. Welcome back to Word <span class="high">Up</span>.</p>
                <p>This is your Word <span class="high">Up</span> Dashboard, providing you with a quick overview of your activities and progress on the site. View your recent activities, your stats and achievements or use the quick menu to launch one of the activities.<br>At the bottom, you’ll see the ‘word of the day’. Do you know what today’s word means in your native language?<br>Have fun and continue learning, <i>one word at a time</i>. </p>
            <?php } ?>
        </div> 
        <!-- ################## END OF INTRO TEXT SECTION ############## -->
    </div>
    <!-- ############# END OF HEADING SECTION ################# -->



<div style="justify-content: left;" class="alternate-button-wrap">
    <button style="width:20%;" class="alternate-button" onclick="showTab('tab1')">SUMMARY DETAILS</button>
    <button style="width:20%;" class="alternate-button" onclick="showTab('tab2')">PROGRESS & ACHIEVEMENTS</button>
    <button style="width:20%;" class="alternate-button" onclick="showTab('tab3')">RECENT ACTIVITY</button>
    <button style="width:20%;" class="alternate-button" onclick="showTab('tab4')">TESTING DETAILS</button>
    <?php
        if ($is_a_site_champ == 'Y') {
    ?>
        <button style="width:20%;" class="alternate-special-button" onclick="showTab('tab5')">SITE CHAMPION!</button>
    <?php
        }
    ?>
    <?php 
        if ($user_data['admin_user'] === 'Y') {
    ?>
        <button style="width:20%;" class="alternate-special-button" onclick="showTab('tab6')">ADMIN</button>
    <?php
        }
    ?>
</div>               


<!-- ############# START OF TAB 1 ################# -->
<div style="border: 0px solid red;" id="tab1" class="tab-container active">
    <div style="border: 0px solid green;" class="card">
                <div class="img-container a">
                    <img src="images/progress.png" width="100px" alt="">
                </div>
                <!-- ########## START OF SUMMARY DETAILS SECTION ########### -->
                <h1>SUMMARY DETAILS</h1>
                    <?php 
                        if($rowcount > 0) {
                    ?>
                    <div class="stats-container-outer">
                        <div class="stat-container-inner-1">
                            <div class="stat-box-horizontal">
                                <p>Total Words:</p>
                                <h1 id="totalWords">0</h1>
                            </div>
                            <div class="stat-box-horizontal">
                                <p>Mastered Words:</p>
                                <h1 id="masteredWords">0</h1>
                            </div>
                            <div class="stat-box-horizontal">
                                <p>Words Tested:</p>
                                <h1 id="totalTested">0</h1>
                            </div>
                        </div>
                        <div class="stat-container-inner-1">
                            <div class="stat-box-horizontal">
                                <p>Longest Streak:</p>
                                <h1 id="consecutiveDays">0</h1>
                            </div>
                            <div class="stat-box-horizontal">
                                <p>Due for testing:</p>
                                <h1 id="nextTestWord"></h1>
                            </div>
                            <div class="stat-box-horizontal">
                                <p>Most tested category:</p>
                                <h1 id="categoryText"></h1>
                            </div>
                        </div> <!-- Close stat-container-inner-1 -->
                        <div class="stat-container-inner-2">
                            <!-- This canvas is for the bar chart visualising a categories average acccuracy. -->
                            <div style="max-width: 600px; max-height: 600px;">
                                <canvas id="accuracyChart"></canvas>
                            </div>
                        </div><!-- Close stat-container-inner-2 -->
                    </div> <!-- Close stat-container-outer -->    

                        <!-- Modal for Expanded Chart -->
                        <div id="chartModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <canvas id="expandedChart"></canvas>
                            </div>
                        </div>

                    <?php
                        } else {
                    ?>
                        <div class="stat-box-horizontal">
                            <p>Nothing to see here right now. Add some words to your library and get more information.</p>
                        </div>
                    <?php
                        }//end rowcount check greater than 0 
                    ?>
        </div>
        <!-- ########## END OF SUMMARY DETAILS SECTION ########### -->
</div>

<!-- ############# START OF TAB 2 ################# -->
<?php if (isset($_SESSION['is_premium']) && $_SESSION['is_premium']): ?>

<div id="tab2" class="tab-container">
    <!-- ########## START OF PROGRESS & ACHIEVEMENTS SECTION ############# -->
    <div style="border: 0px solid red;" class="card">
            <div class="img-container b">
                <img src="images/statistics.png" width="100px" alt="">
            </div>
            <h1>PROGRESS & ACHIEVEMENTS</h1>
            <?php 
                if($rowcount > 0) {
            ?>
                <!-- DIV, containing guage graphs re: adding words.  -->
                <div class="guages-container">
                    <canvas id="streakWordsGauge" ></canvas>
                    <div class="guages-container-text">
                        <h1>Reaching your vocabulary goal?</h1>
                        <p>You set a target of adding a word each day for <strong><?php echo $max_word_streak ?></strong> days. You are on day <strong><?php echo $current_word_streak ?></strong>.</p>
                    </div>
                    <canvas id="streakGauge" ></canvas>
                    <div class="guages-container-text">
                        <h1>Reaching your testing goal?</h1>
                        <p>You set a target of testing everyday for <strong><?php echo $max_test_streak ?></strong> days. You are on day <strong><?php echo $current_test_streak ?></strong>.</p>
                    </div>
                </div>
                <p>&nbsp;</p>
            <?php
                //########## PROGRESS BARS CODE ####################
                //Call function to get % of words user has mastered.
                $mastered_percentage = calc_percentage_mastered($rowcount, $mastered_count);
                
                //Call function to get % of words user has not tested.
                $not_tested_percentage = calc_percentage_not_tested($rowcount, $not_tested_count);

                //Call function to count number of badges awarded to user. Will return a percentage.
                $badge_completion_percentage = calc_overall_badge_completion();
            ?>
            <div class="progress-container-parent">
                <!-- Can't get styles to work for progress-bar from .css so hardcoding. -->

                <div class="progress-container-outer">
                    <div class="progress-container-inner">
                        <div id="progress-container">   
                            <div id="progress-bar-1" class="progress-bar" style="color: white; font-weight: 800; padding-left: 30px; line-height: 50px;">0%</div>
                        </div>
                        <p style="font-weight: 500;">WORDS MASTERED</p>
                </div>
                </div>
                <div class="progress-container-outer">
                <div class="progress-container-inner">
                    <div id="progress-container">
                        <div id="progress-bar-2" class="progress-bar" style="color: white; font-weight: 800; padding-left: 30px; line-height: 50px;">0%</div>
                    </div>
                    <p style="font-weight: 500;">WORDS YET TO TEST</p>
                </div>
                </div>
                <div class="progress-container-outer">
                <div class="progress-container-inner">
                    <div id="progress-container">
                        <div id="progress-bar-3" class="progress-bar" style="color: white; font-weight: 800; padding-left: 30px; line-height: 50px;">0%</div>
                    </div>
                    <p style="font-weight: 500;">BADGES COMPLETE</p>
                </div>
                </div>

            </div>     
            <?php } else {
            ?>
                <div class="stat-box-horizontal">
                    <p>There is no progress to report just yet.</p>
                </div>
            <?php
            }
            ?>
        </div>
        <!-- ########## END OF PROGRESS & ACHIEVEMENTS SECTION ############# -->
</div>
<?php else: ?>
    <div id="locked" class="tab-container">
        <h2>🔒 Premium Content Locked</h2>
        <p><a href="subscribe.php">Upgrade to Premium</a> to unlock all content.</p>
    </div> 

<?php endif; ?>


<!-- ########## START OF TAB 3 SECTION ############# -->
<div id="tab3" class="tab-container">
     <!-- ########## START OF RECENT ACTIVITY SECTION ############# -->
     <div class="card">
            <div class="img-container c">
                <img src="images/calendar.png" width="100px" alt="">
            </div>
                <h1>RECENT ACTIVITY</h1>
                    <?php
                    
                        //Check user has added some words
                        if($rowcount > 0) {
                    ?>
                    <div class="recent-container-outer">
                        <div class="recent-container-inner-1">  
                    <?php
                            //Call function to get users last 5 words
                            $five_words = last_five_words();

                            //If user has less than 5 words, they will not see recent activity.
                            if (mysqli_num_rows($five_words) < 5) {
                    ?>
                            <h4>Add at least 5 words to see some activity.</h4>
                    <?php
                            } else {
                    ?>
                        <h4>Your last <?php echo mysqli_num_rows($five_words) ?> words: </h4>

                            <ul class="recent-words">
                    <?php  
                            while ($new_row = $five_words->fetch_assoc()) { 
                            ?>
                            <!-- Cycle through array of 5 words. -->
                            <li><?php echo $new_row["fr_text"]; ?> </li>
                    <?php 
                            }
                    ?>
                    </ul>
                    <?php 
                        } //end if statement checking for 5 rows of recent words.
                    ?>
                    </div> <!-- end recent-container-inner-1 -->
                    <div class="recent-container-inner-1">
                    <?php
                            //Call function to get users last 5 mastered
                            $five_mastered = last_five_mastered();

                            if(mysqli_num_rows($five_mastered) < 5) {
                    ?>
                            <h4> Master at least 5 words to see activity.</h4>
                    <?php
                            } else {
                    ?>
                            <h4>Your last <?php mysqli_num_rows($five_mastered) ?> words mastered:</h4>
                            <ul class="recent-words">
                    <?php  
                            while ($new_row = $five_mastered->fetch_assoc()) { 
                            ?>
                            <!-- Cycle through array of 5 words. -->
                            <li><?php echo $new_row["fr_text"]; ?> </li>
                    <?php 
                            }
                    ?>
                    </ul>
                    <?php 
                            }//end if statement checking for more than 5 mastered words.
                    ?>
                    </div> <!-- end recent-container-inner-1 -->
                    <div class="recent-container-inner-2">
                        <!-- Start of code for test results graph. -->
                        <div style="max-width: 600px; max-height: 600px;">
                            <canvas id="testResultsChart"></canvas>
                        </div>
                    </div>
                    <div class="recent-container-inner-2">
                        <!-- Start of code for test results graph. -->
                        <div style="max-width: 600px; max-height: 600px;">
                            <canvas id="myPieChart"></canvas>
                        </div>
                    </div>
                </div> <!-- end recent-container-outer -->


                            <!-- Modal for Expanded Test Results Chart -->
                        <div id="chartModalTestResult" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <canvas id="modalChart"></canvas>
                            </div>
                        </div>
            <p>&nbsp;</p>



            <?php
                } else {
            ?>
                <div class="stat-box-horizontal">
                    <p>You have no recent activity recorded.</p>
                </div>
            <?php
                }//end rowcount check for greater than 0
            ?>
        </div>
        <!-- ########## END OF RECENT ACTIVITY SECTION ############# -->
</div>

<!-- ########## START OF TAB 4 SECTION ############# -->
<div id="tab4" class="tab-container">
        <!-- ############# START OF DETAILED TESTING CARD ################# -->
        <div class="card">
            <div class="img-container d">
                <img src="images/lang-tips.png" width="100px" alt="">
            </div>
            <h1>DETAILED TESTING STATISTICS</h1>
            <!-- START OF CODE FOR HIGH SCORING WORDS SECTION -->
            <?php

                //Call function to look for potential words to be marked as mastered
                // $potential_mastered = find_potential_mastered_words();

                // //Start preparation of data for word cloud. Move words from first array to new array.
                // foreach ($potential_mastered as $row) {
                //     $cloud_word[] = $row['word'];
                // }
                
                // //Assign a weighting to each value.
                // $wordData = array_map(function($word) {
                //     return [$word, rand(10, 30)]; // Random weight for demonstration
                //     //return [$word,15];
                // }, $cloud_word);

                // //Convert array to JSON to pass to wordcloud script.
                // $jsonData = json_encode($wordData);
            ?>

            <!-- <div class="test-container-outer">
                <div class="test-container-inner">
                    

                    
                </div>
            </div> -->

            <!-- START OF CODE FOR QUICK STATS SECTION -->
            <?php

                //Declare variables.
                $avg_word_accuracy = 0;
                $category_accuracy_title = '';
                $category_accuracy = 0;
                $test_streak = 0;

            if ($rowcount > 0) {
                  //Call functions to calc quick stats - average word accuracy, best category for accuracy, current days testing streak
                $avg_word_accuracy = calc_average_word_accuracy();


                //If there are no words that fit the criteria, don't show this section.
                if ($avg_word_accuracy == 0) {
                // if ($row_count > 0) {
            ?>
                <div class="stat-box-horizontal">
                    <p>You have no tests recorded yet.</p>
                </div>
            <?php
                        } else {

                      //  $current_test_streak = find_test_streak();

                      //  $total_tests = number_of_user_tests();
        
                        //$category_stats = calc_category_highest_and_lowest_accuracy();
            ?>
                <div class="tests-container-outer">
                    <div class="tests-container-inner-1">
                        <div class="tests-box-horizontal">
                            <p>Average word accuracy:</p>
                            <h1 id="avgWordAccuracy">0</h1>
                        </div>
                        <div class="tests-box-horizontal">
                            <p>Current test streak:</p>
                            <h1 id="currentTestStreak">0</h1>
                        </div>
                        <div class="tests-box-horizontal">
                            <p>Total tests:</p>
                            <h1 id="numTestsTaken">0</h1>
                        </div>
                    </div> <!-- close tests-container-inner-1 -->
                    <div class="tests-container-inner-1">
                        <div class="tests-box-horizontal">
                            <p>Last test score:</p>
                            <h1 id="lastTestScore">0</h1>
                        </div>
                        <div class="tests-box-horizontal">
                            <p>Highest accuracy: </p>
                            <h1 id="highestCategoryAcc"></h1>
                            <h1 id="highestCategoryAccScore"></h1>
                        </div>
                        <div class="tests-box-horizontal">
                            <p>Lowest accuracy: </p>
                            <h1 id="lowestCategoryAcc"></h1>
                            <h1 id="lowestCategoryAccScore"></h1>
                        </div>
                    </div> <!-- Close tests-container-inner-1 -->
                    <div class="tests-container-inner-2">


                    <p>You consistently score 100% on the words below.</p>
                    <ul class="recent-words">
                        <?php

                            foreach ($potential_mastered as $row) {
                                $vocab_id_array[] = $row['vocab_id']; //want to pass this to test_record.php
                        ?>
                            <li> <?php    echo ($row['word']); ?> </li>
                        <?php
                            }
                        ?>
                    </ul>

                    <?php

                        //convert vocab_id array to JSON
                        $json_vocab_id = json_encode($vocab_id_array);

                        //URL encode the JSON
                        $encoded_vocab_id = urlencode($json_vocab_id);

                    ?>

                    <form action="auto-mastery-update.php?vocab_id=<?php echo $encoded_vocab_id ?>" method="POST" class="form-card">
                        <div class="alternate-button-wrap">
                            <button class="alternate-button" name="MarkMastered" type="submit">MASTER WORDS</button>
                        </div>
                    </form>

                    </div> <!-- Close test-container-inner-2 -->

                    <div class="tests-container-inner-2">


                    <!-- START OF CODE FOR LOW SCORING WORDS SECTION -->
                    <?php
                            //Call function to look for potential words to be marked as mastered
                            $low_score_words = find_low_score_words();

                    ?>

                    <p>The following is a list of words you struggle with:</p>
                    <ul class="recent-words">
                        <?php
                            //$potential_mastered = find_potential_mastered_words();

                            foreach ($low_score_words as $row) {
                                $vocab_id_low_scores_array[] = $row['vocab_id']; //want to pass this to test_record.php
                        ?>

                            <li> <?php echo ($row['word']); ?> [<?php echo ($row['category_desc']); ?>] </li>
                        <?php
                                if ($row === 5) {
                                    break; // Exit the loop when 5 is reached. The SQL can return an array of 15 but keeping to 5 for display purposes
                                }

                            }
                        ?>
                    </ul>

                    <?php

                        //convert vocab_id array to JSON
                        $json_vocab_id_low_scores = json_encode($vocab_id_low_scores_array);

                        //URL encode the JSON
                        $encoded_vocab_id_low_scores = urlencode($json_vocab_id_low_scores);

                    ?>

                    <form action="test.php?vocab_id=<?php echo $encoded_vocab_id_low_scores ?>" method="POST" class="form-card">
                        <div class="alternate-button-wrap">
                            <input type="hidden" name="form_identifier" value="HardTest">
                            <button class="alternate-button" name="HardTest" type="submit">TAKE HARD TEST</button>
                        </div> 
                    </form>


                    </div> <!-- Close tests-container-inner-2 -->
                </div> <!-- Close tests-container-outer -->
                <div class="tests-container-outer">
                    <div class="tests-container-inner-3">
                        <p>The average duration between adding a word and marking it as <i>mastered</i> is <strong><?php echo $average_to_mastery; ?></strong> days for you. </p>
                    </div>
                </div>
                <?php
                    } //end of if statement to check how many rows in array
                } else {
                ?>
                    <div class="stat-box-horizontal">
                        <p>You have no tests recorded yet.</p>
                    </div>
                <?php
                    }
                ?>
        </div>
        <!-- ############# CLOSE OF DETAILED TESTING CARD ################# -->
</div>

<!-- ############# START OF TAB 5 ################# -->
<div id="tab5" class="tab-container">
    <div style="border: 0px solid green;" class="card">
        <div style="border: 0px solid blue;" class="img-container a">
            <img src="images/progress.png" width="100px" alt="">
        </div>
        <!-- ########## ACHIEVEMENT SECTION START ############ -->
        <h1>SITE CHAMPION</h1>

        <div style="border: 0px solid red;" class="drop-container">
        <?php 
            //Check if current user has the most words
            if ($am_i_word_leader == 'Y') {
        ?>
            <div class="drop">
                 <p><i class="fa-solid fa-ranking-star fa-2xl" style="color: #b0f0cf;"></i></p>
            </div>
        <?php
            }
        ?>
        <?php 
            //Check if current user has done the most tests.
            if ($am_i_tests_leader == 'Y') {
        ?>
            <div class="drop">
                <p><i class="fa-solid fa-medal fa-2xl" style="color: #b0f0cf;"></i></p>
            </div>
        <?php
            }
        ?>
        <?php 
            //Putting this check here so that the award will display without user having to visit the 'badges.php' page.
            if ($am_i_platinum == 'Y') {
        ?>
                <div class="drop">
                    <p><i class="fa-solid fa-trophy fa-2xl" style="color: #b0f0cf;"></i></p>
                </div>
        <?php
            }
        ?>
        </div>
    </div>
        <!-- ########## END OF ACHIEVEMENTS SECTION ########### -->
</div>
<!-- ############ END OF TAB 5  ############## -->

<!-- ############# START OF TAB 6 ################# -->
<div id="tab6" class="tab-container">
    <div style="border: 0px solid green;" class="card">
        <div style="border: 0px solid blue;" class="img-container a">
            <img src="images/progress.png" width="100px" alt="">
        </div>
        <!-- ########## ADMIN SECTION START ############ -->
        <h1>ADMIN SECTION</h1>
        <div class="admin-container-outer">
            <div class="admin-container-inner-1">
            <?php 
                $message_rowcount = check_messages(); //Call function to notify of new messages
                        
                if($message_rowcount > 0) { 
            ?>
                <p>A new message(s) has been submitted.</p>
            <?php 
                } 
            ?>
                <form class="form-card" method="POST" action="mark-read.php">
                    <div class="alternate-input">
                        <div class="alternate-button-wrap">
                            <button class="alternate-button" type="submit">MARK AS READ</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="admin-container-inner-2">
                <!-- Call function to figure work out some stats to display to admin user. -->
                <div class="admin-stat-style">
                    <p>Total Registered Users:</p>
                    <h1 id="totalRegisteredUsers">0</h1>
                </div>
                <div class="admin-stat-style">
                    <p>Total Recorded Words:</p>
                    <h1 id="totalRecordedWords">0</h1>
                </div>
                <div class="admin-stat-style">
                    <p>Total Tests Taken:</p>
                    <h1 id="totalTestsTaken">0</h1>
                </div>
            </div>
        </div>

    </div>
        <!-- ########## END OF ADMIN SECTION ########### -->
</div>
<!-- ############ END OF TAB 6  ############## -->




    <!-- ############# START OF QUICK MENU ROW ################# -->
    <div class="cards-wrapper">
        <div style="width:80%;" class="card">
            <div class="img-container d">
                <img src="images/icon-1.png" width="100px" alt="">
            </div>
            <h1>QUICK MENU</h1>
            <form action="quick-links.php" method="post" class="form-card">
                <div class="alternate-button-wrap">
                    <!-- <input type="submit" name="SubmitButton" class="btn btn-secondary"> -->
                    <button style="width:20%;" class="alternate-button" name="AddWordButton" type="submit">ADD A WORD</button><p></p>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <button style="width:20%;" class="alternate-button" name="ViewListButton" type="submit">VIEW LIBRARY</button><p></p>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <button style="width:20%;" class="alternate-button" name="TakeTestButton" type="submit">TAKE A TEST</button><p></p>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <button style="width:20%;" class="alternate-button" name="ViewBadgesButton" type="submit">VIEW BADGES</button><p></p>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <button style="width:20%;" class="alternate-button" name="ViewUserPreferences" type="submit">USER PREFERENCES</button><p></p>
                </div>
            </form>
            </div>
        </div>

        
    </div>
    <!-- ############# END OF QUICK MENU ROW ################# -->

    <!-- ############# START OF BOTTOM ROW ################# -->
    <div class="cards-wrapper">
        <!-- ############# START OF WORD OF THE DAY CARD ################# -->
        <div style="width:30%;" class="card">
            <div class="img-container a">
                <img src="images/word.png" width="100px" alt="">
            </div>
            <h1>WORD OF DAY</h1>
            <!-- <p>Your random word is <strong><i> <?php echo $session_word ?> </i></strong>. </p> -->
            <blockquote>
                <?php 
                    if ($_SESSION['session_tl'] == 'XX') {
                ?>
                    <h4>You need to set a <i>Target Language</i> in User Preferences to see a Word of the Day.</h4>
                <?php
                    } else {
                ?>
                    <?php $user_tl_upper = strtoupper($_SESSION['session_tl']); ?>
                    <h>Random word (<?php echo $user_tl_upper ?>) for you:</h3>
                    <p style="padding-left:50px;"><strong><i> <?php echo $_SESSION['session_word']; ?> </i></strong></p>
                    <p>Do you know what it means?</p>
                <?php
                    }
                ?>
            </blockquote>
        </div>
        <!-- ############# END OF WORD OF THE DAY CARD ################# -->


        <div style="width:30%;" class="card">
            <div class="img-container a">
                <img src="images/word.png" width="100px" alt="">
            </div>
            <h1>YOUR WORD CLOUD</h1>
            <div class="cloud-container">
                <?php 
                    if ($rowcount > 0) {
                ?>
                        <canvas id="wordcloud" width="600" height="400"></canvas> 
                <?php 
                    } else {
                ?>
                    <blockquote><h4>Start adding words to your library to fill out your 'Word Cloud'.</h4></blockquote>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>


        <!-- ###################### SCRIPTS SECTION #########################-->
        
        <script>
            /**
             *  This script controls the tabbing between on the main page.
             */
        //     function showTab(tabId) {
        //         //Hide all tabs
        //         let tabs = document.querySelectorAll('.tab-container');
        //         tabs.forEach (tab => tab.classList.remove('active'));

        //         //Show the selected tab
        //         document.getElementById(tabId).classList.add('active');


        //             // If locked, show paywall message fuck
        //                 // if (!document.getElementById("tab-container" + tabId)) {
        //                 // document.getElementById("locked").style.display = "block";
        //                 // }
        //     }
        // </script>



 <script>
function showTab(tabId) {
    document.querySelectorAll('.tab-container').forEach(tab => tab.style.display = 'none'); // Hide all tabs
    document.getElementById("tab-container" + tabId)?.style.display = "block"; // Show selected tab

    // If locked, show paywall message
    if (!document.getElementById("tab" + tabId)) {
        document.getElementById("locked").style.display = "block";
    }
}
</script> 


        <?php       
        if ($rowcount > 0): ?>
        <script>
            /**
             *  This script controls the dynamic progress bars. 
             *      1. Words mastered
             *      2. Words not tested
             *      3. Badges completed.
             */
                let progress1 = 0;
                let progress2 = 0;
                let progress3 = 0;
                let maxProgress1 = <?php echo $mastered_percentage ?>;
                let maxProgress2 = <?php echo $not_tested_percentage ?>;
                let maxProgress3 = <?php echo $badge_completion_percentage ?>;

                let interval1, interval2, interval3;

                //Mastered progress bar
                if (maxProgress1 > 0) {
                    function updateProgress1() {
                        if (progress1 < maxProgress1) {
                            progress1 += 1;
                            document.getElementById("progress-bar-1").style.width = progress1 + "%";
                            document.getElementById("progress-bar-1").innerText = progress1 + "%";
                        } else {
                            clearInterval(interval1);
                        }
                    }
                }
                
                //Tested progress bar
                function updateProgress2() {
                    if (progress2 < maxProgress2) {
                        progress2 += 1;
                        document.getElementById("progress-bar-2").style.width = progress2 + "%";
                        document.getElementById("progress-bar-2").innerText = progress2 + "%";
                    } else {
                        clearInterval(interval2);
                    }
                }

                //Badges progress bar
                function updateProgress3() {
                    if (progress3 < maxProgress3) {
                        progress3 += 1;
                        document.getElementById("progress-bar-3").style.width = progress3 + "%";
                        document.getElementById("progress-bar-3").innerText = progress3 + "%";
                    } else {
                        clearInterval(interval3);
                    }
                }

                //There can be only one 'window.onload' call in a file, this is the one. 
                //Other scripts will uses EventListener
                window.onload = function() {
                    interval1 = setInterval(updateProgress1, 50);
                    interval2 = setInterval(updateProgress2, 100);
                    interval3 = setInterval(updateProgress3, 75);
                };
        </script>
        <?php endif; ?>

        <?php if ($rowcount > 0): ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            const stats = [
                { elementId: 'totalWords', target: <?php echo $rowcount ?? 0;?> },
                { elementId: 'masteredWords', target: <?php echo $mastered_count ?? 0; ?> },
                { elementId: 'totalTested', target: <?php echo $tested_count ?? 0; ?> },
                { elementId: 'consecutiveDays', target: <?php echo $longest_streak ?? 0; ?> },
                { elementId: 'lastTestScore', target: <?php echo $last_score ?? 0; ?> },
                { elementId: 'avgWordAccuracy', target: <?php echo $avg_word_accuracy ?? 0; ?> },
                { elementId: 'currentTestStreak', target: <?php echo $current_test_streak ?? 0; ?>},
                { elementId: 'numTestsTaken', target: <?php echo $total_tests ?? 0; ?>},
                { elementId: 'highestCategoryAccScore', target: <?php echo round($category_stats['highest']['score'] ?? 0); ?> },
                { elementId: 'lowestCategoryAccScore', target: <?php echo round($category_stats['lowest']['score'] ?? 0); ?> },
                { elementId: 'totalRegisteredUsers', target: <?php echo $num_registered_users ?? 0; ?> },
                { elementId: 'totalRecordedWords', target: <?php echo $total_num_words_in_db ?? 0; ?> },
                { elementId: 'totalTestsTaken', target: <?php echo $total_num_tests_in_db ?? 0; ?>}
            ];

            //Setting which fields are percentages
            const percentageFields = ['lastTestScore', 'avgWordAccuracy', 'highestCategoryAccScore', 'lowestCategoryAccScore'];

            function animateCount(elementId, targetValue, isPercentage = false) {
                const element = document.getElementById(elementId);
                
                // Check if the element exists before attempting to modify it
                if (!element) {
                    console.error(`Element with ID '${elementId}' not found.`);
                    return;
                }

                let currentCount = 0;
                const increment = targetValue / 50; // Controls speed and smoothness

                const counter = setInterval(() => {
                    currentCount += increment;

                    if (currentCount >= targetValue) {
                        element.textContent = isPercentage 
                            ? `${targetValue}%` 
                            : Math.floor(targetValue);
                        clearInterval(counter);

                        // Add bounce effect when counting completes
                        element.style.animation = 'bounce 0.5s ease-out';
                    } else {
                        element.textContent = isPercentage 
                            ? `${Math.ceil(currentCount)}%` 
                            : Math.ceil(currentCount);
                    }
                }, 20);
            }

            // Run the animation
            stats.forEach(stat => {
                const isPercentage = percentageFields.includes(stat.elementId);
                animateCount(stat.elementId, stat.target, isPercentage);
            });

        });
    </script>
<?php endif; ?>



        <?php       
        if ($rowcount > 0): ?>
        <script>
            /**
             *  This script is used to animate a string. 
             *  In this particular code it is animating the string returned to calculate the most tested category.
             *  It useds EventListener instead of on.WindowLoad because the latter can only be used once in a file.
             */
            const nextWord = "<?php echo $next_word ?? 0; ?>";
           const mostTestedCategory = "<?php echo $most_tested_category ?? 0; ?>";
           const highestAccuracyCategory = "<?php echo $category_stats['highest']['category'] ?? 0 ?>"
           const lowestCategoryAccuracy = "<?php echo $category_stats['lowest']['category'] ?? 0 ?>"

            function typeLetterByLetter(text, elementId, index = 0) {
                const element = document.getElementById(elementId);

                if (index < text.length) {
                element.textContent += text.charAt(index);
                setTimeout(() => typeLetterByLetter(text, elementId, index + 1), 100);
                }
            }

            // Trigger both animations when the page loads
            window.addEventListener('load', () => {
                typeLetterByLetter(nextWord, 'nextTestWord');
                typeLetterByLetter(mostTestedCategory, 'categoryText');
                typeLetterByLetter(highestAccuracyCategory, 'highestCategoryAcc');
                typeLetterByLetter(lowestCategoryAccuracy, 'lowestCategoryAcc');
            });
        </script>
        <?php endif; ?>


        <?php       
        if ($rowcount > 0): ?>
        <script>
            /**
             *  This script is used to generate the line graph showing the users most recent test results. 
             */

             let originalChart, modalChart;

            // Fetch data from graphs.php
            fetch('graphs-test-results.php?fetch=true')
            .then(response => response.json())
            .then(data => {


                const ctx = document.getElementById('testResultsChart').getContext('2d');
                originalChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.x, // Dates on X-axis
                        datasets: [{
                            label: 'Recent Test Scores',
                            data: data.y, // Scores on Y-axis
                            borderColor: 'rgb(10, 246, 53)',
                            backgroundColor: 'rgb(30, 23, 79)',
                            borderWidth: 2,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
            x: {
                ticks: {
                    font: {
                        family: 'sans-serif',  // X-axis font family
                        size: 14,         // X-axis font size
                        weight: 'bold',   // Bold text
                        style: 'italic'   // Italic text
                    },
                    color: 'black' // X-axis text color
                },
                title: {
                    display: true,
                    text: '',  // X-axis label
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'black'
                }
            },
            y: {
                ticks: {
                    font: {
                        family: 'sans-serif',  // Y-axis font family
                        size: 14,           // Y-axis font size
                        weight: 'bold'      // Bold text
                    },
                    color: 'black' // Y-axis text color
                },
                title: {
                    display: true,
                    text: 'Scores (%)',  // Y-axis label
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: 'black'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top', // Positions: 'top', 'bottom', 'left', 'right'
                labels: {
                    font: {
                        family: 'sans-serif', // Legend font family
                        size: 14, // Legend font size
                        weight: 'bold' // Legend font weight
                    },
                    color: 'black', // Legend text color
                    boxWidth: 20, // Width of legend color box
                    padding: 15 // Space between legend items
                }
            }
        }
                    }
                
                });
            })
            .catch(error => console.error('Error fetching data:', error));






            // Get modal elements
            const modal = document.getElementById("chartModalTestResult");
            const modalCanvas = document.getElementById("modalChart");
            const closeBtn = document.querySelector(".close");


            if (!window.modalInitialized) {
                document.getElementById("testResultsChart").addEventListener("click", function (event) {
                event.stopPropagation(); // Prevent event bubbling
                modal.style.display = "flex";

                if (modalChart) {
                    modalChart.destroy();
                }

                // Clone original chart config
                const modalCtx = modalCanvas.getContext('2d');
                    modalChart = new Chart(modalCtx, {
                    type: originalChart.config.type,
                    data: originalChart.config.data,
                    options: { responsive: true, maintainAspectRatio: false }
                });
            });

                window.modalInitialized = true;
            }

            // Close Modal
            document.querySelector('.close').addEventListener('click', function () {
                document.getElementById('chartModal').style.display = 'none';
            });

        
            // Close modal
            closeBtn.addEventListener("click", function () {
                modal.style.display = "none";
            });

        // Close modal when clicking outside
        // window.addEventListener("click", function (event) {
        //     if (event.target == modal) {
        //         modal.style.display = "none";
        //     }
        // });

            // Close modal when clicking outside the chart
            modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
        </script>
        <?php endif; ?>

        <?php       
        if ($rowcount > 0): ?>
        <script>
            /**
             *  This scrip is used to generate the 'doughnut' chart showing the category breakdown.
             */
            var barColors = [
            "#D53E4F","#F46D43","#FDAE61","#FEE088","#FFFFBF",
            "#E6F596","#ABDDA4","#66C2A5","#3288BD"];

            // Fetch data from graphs.php
            fetch('graph-categories.php?fetch=true')
            .then(response => response.json())
            .then(data => {
                console.log("Fetched Data:", data); // Debugging output
                console.log("X-axis values (catgories):", data.x);
                console.log("Y-axis values (count):", data.y);

                if (!data.x || !data.y || data.x.length === 0 || data.y.length === 0) {
                    console.error("Error: Data arrays are empty.");
                    return;
                }
        
            var ctx = document.getElementById('myPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.x, // Dates on X-axis
                    datasets: [{
                        backgroundColor: barColors,
                        borderColor: "rgba(0,0,255,0.1)", 
                        label: 'Category Breakdown',
                        data: data.y, // Scores on Y-axis
                        borderColor: 'black',
                        fill: false
                    }]
                },
                // options: {
                //     legend: {display: true, "labels": {"fontSize":13,}},
                //     scales: {
                // // xAxes: [{ ticks: { fontSize: 16}}],
                //     }}
                options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,  // Enable the title
                    text: 'CATEGORY BREAKDOWN',  // The title text
                    font: {
                        family: 'sans-serif',  // Set custom font family
                        size: 18,  // Set font size
                        weight: 'normal'  // Font weight
                    },
                    padding: {
                        top: 20,  // Padding above the title
                        bottom: 10  // Padding below the title
                    }
                },
                legend: {
                    display: true,  // Show legend
                    position: 'right',  // You can change this to 'bottom', 'left', 'right'
                    labels: {
                        font: {
                            family: 'sans-serif',  // Set custom font family
                            size: 14,  // Set font size
                            weight: 'normal'  // Font weight
                        },
                        color: 'black'  // Set legend text color
                    }
                }
            }
        }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
        </script>
        <?php endif; ?>

        <?php       
        if ($rowcount > 0): ?>
        <script>
            /**
             * Script for creating the guage chart for visualising user test activity.
             * Added PHP check just before this JavaScript is called. If $row_count is 0, do not run this script. A value
             * of 0 means the user has no words on tb_vocab.
             */
            // Fetch data from the PHP file
            fetch('graph-user-test-streak.php')
            .then(response => response.json())
            .then(data => {
                const currentStreak = <?php echo $current_test_streak ?>;
                const maxStreak = data.maxStreak;
                const ctx = document.getElementById('streakGauge').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Current Streak', 'Left to Target'], // Labels for the legend
                    datasets: [{
                    data: [currentStreak, maxStreak - currentStreak],
                    backgroundColor: ['#FDAE61', '#D53E4F'],    //filled, empty colours
                    borderWidth: 1
                    }]
                },
                options: {
                    rotation: -90,
                    circumference: 180,
                    cutout: '65%',  //controls thickness
                    plugins: {
                    legend: { display: true, position: 'bottom' },
                    tooltip: { enabled: true }
                    }
                }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
        </script>
        <?php endif; ?>



        <?php 
        if ($rowcount > 0): ?>
        <script>
            /**
             * Script for creating the guage chart for visualising user word adding activity.
             * Added PHP check just before this JavaScript is called. If $row_count is 0, do not run this script. A value
             * of 0 means the user has no words on tb_vocab.
             */
            // Fetch data from the PHP file
            fetch('graph-user-words-streak.php')
            .then(response => response.json())
            .then(data => {
                const currentStreak = <?php echo $current_word_streak ?>;
                const maxStreak = data.maxWordStreak;
                const ctx = document.getElementById('streakWordsGauge').getContext('2d');

            // Shadow Plugin
            const shadowPlugin = {
                id: 'shadowEffect',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    ctx.save();
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.3)'; // Shadow color
                    ctx.shadowBlur = 10; // Blur effect
                    ctx.shadowOffsetX = 4; // Horizontal shadow
                    ctx.shadowOffsetY = 4; // Vertical shadow
                },
                afterDraw: (chart) => {
                chart.ctx.restore();
                }
            };

            Chart.register(shadowPlugin); // Register the plugin

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Current Streak', 'Left to Target'], // Labels for the legend
                    datasets: [{
                    data: [currentStreak, maxStreak - currentStreak],
                    backgroundColor: ['#ABDDA4', '#66C2A5'],    //filled, empty colours
                    borderWidth: 1
                    }]
                },
                options: {
                    rotation: -90,
                    circumference: 180,
                    cutout: '65%',  //controls thickness
                    plugins: {
                    legend: { display: true, position: 'bottom' },
                    tooltip: { enabled: true }
                    }
                },
                plugins: [shadowPlugin] // Apply shadow plugin
                });
            })
            .catch(error => console.error('Error fetching data:', error));
        </script>
        <?php endif; ?>


        <?php 
        if ($rowcount > 0): ?>
        <script>
            /**
             * Script for creating the vertical bar chart representing category accuracy averages. 
             * Added PHP check just before this JavaScript is called. If $row_count is 0, do not run this script. A value
             * of 0 means the user has no words on tb_vocab.
             */

             var barColors = [
            "#D53E4F","#F46D43","#FDAE61","#FEE088","#FFFFBF",
            "#E6F596","#ABDDA4","#66C2A5","#3288BD"];

            const ctx = document.getElementById('accuracyChart').getContext('2d');
            let accuracyChart;


            // Function to fetch data from get-data.php
            async function fetchChartData() {
            try {
                const response = await fetch('graph-category-accuracy.php');
                
                if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
                }

                const data = await response.json();

                const categories = data.map(item => item.category);
                const averages = data.map(item => item.average);

                // If chart exists, destroy to refresh the data
                if (accuracyChart) {
                accuracyChart.destroy();
                }

                // Create the chart
                accuracyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: categories,
                    datasets: [{
                    label: 'Category Average Accuracy',
                    data: averages,
                    backgroundColor: barColors,
                    borderColor: 'rgba(2, 2, 2, 1)',
                    borderWidth: 2
                    }]
                },
                options: {
                responsive: true,
                plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
                },
                scales: {
                y: {
                    beginAtZero: true
                }
                },
                animation: {
                duration: 800,
                easing: 'easeInOutQuart'
                }
            }
            });

            // Open Modal on Click
            document.getElementById('accuracyChart').addEventListener('click', function () {
            document.getElementById('chartModal').style.display = 'flex';

            // Render expanded chart
            const expandedCtx = document.getElementById('expandedChart').getContext('2d');
            new Chart(expandedCtx, {
                type: 'bar',
                data: {
                    labels: categories,
                    datasets: [{
                    label: 'Category Average Accuracy',
                    data: averages,
                    backgroundColor: barColors,
                    borderColor: 'rgba(2, 2, 2, 1)',
                    borderWidth: 2
                    }]
                },
                options: {
                responsive: true,
                plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
                },
                scales: {
                y: {
                    beginAtZero: true
                }
                },
                animation: {
                duration: 800,
                easing: 'easeInOutQuart'
                }
            }
            });
        });

                // Close Modal
                document.querySelector('.close').addEventListener('click', function () {
                    document.getElementById('chartModal').style.display = 'none';
                });

                // Close Modal if clicking outside content
                window.addEventListener('click', function (event) {
                    if (event.target === document.getElementById('chartModal')) {
                        document.getElementById('chartModal').style.display = 'none';
                    }
                });



            } catch (error) {
                console.error('Error fetching data:', error);
                alert('Failed to load chart data.');
            }
            }


            
            // Fetch data when page loads
            fetchChartData();
        </script>
        <?php endif; ?>

        <?php 
        if ($rowcount > 0): ?>
        <script>
            /**
             *      This script is used to generate the wordcloud. It currently holds words that should me marked as mastered.
             *      Added PHP check just before this JavaScript is called. If $row_count is 0, do not run this script. A value
             *      of 0 means the user has no words on tb_vocab.
             */
            const wordData = <?php echo json_encode($wordData); ?>;

            const options = {
                list: wordData, // List of words and frequencies
                gridSize: 15, // Size of the grid (in pixels)
                weightFactor: 1, // The scale factor of word frequency to font size
                fontFamily: 'Rubin, Arial, sans-serif', // Font used in the word cloud
                //color: 'random-dark', // Use random dark colors for the words
                //backgroundColor: '#f4f4f4', // Background color
                color: function() {
                return ([ "#D53E4F","#F46D43","#FDAE61","#FEE088",
                            "#FFFFBF","#E6F596","#ABDDA4","#66C2A5",
                            "#3288BD"])[Math.floor(Math.random() * 9)]
                },
                backgroundColor: "#FFFFFF",
                hover: (item) => console.log(item),
                clearCanvas: true,
                drawMask: false,
                    rotateRatio: 0.5, // Rotation ratio (words can be rotated randomly)
                    rotationSteps: 2, // Number of steps for rotating
                    hover: function(word, event) {
                    console.log('Hovered over:', word);
                    }
                };

            // Create the word cloud
            WordCloud(document.getElementById('wordcloud'), options);
        </script>
        <?php endif; ?>

		</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>