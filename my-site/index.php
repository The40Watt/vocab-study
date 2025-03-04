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

    02-03-25: Added style class 'recent' that will make lists dipslay horizontal. 
            
              Added change to recent activity related to mastery. A call to new function to retrieve last five mastered words. 

              Added new function to calculate the percentage of mastered words out of the total number of words. 
    
    04-03-25: Added new function to calculate the average time it takes to master a word. The output is display in the users stats.

              Fixed bug where the display of the page was broken when the user is new and has no words. Added multiple checks for rowcount (of words) greater 
              than zero.


-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
    include("include/file-functions.php");
    include("include/error-logging.php");

	
    //Declare variables
    $cnt = 0;
    $word = '';
    $test_cnt = 0;
    $rand_word = '';

    //Variable to define new user (no words on tbvocab)
    $new_user = '';

	$user_data = check_login($conn); //if logged in, this variable will contain the user data
	$rowcount = count_records($cnt);

    if ($rowcount > 0) {
        $next_word = next_word($word);
        $tested_count = number_of_tests($test_cnt);
        $mastered_count = count_mastered_words();
        $not_tested_count = number_not_tested();
        $average_to_mastery = calc_average_time_mastery();
    }

    //If the random word for the session has already been set, then skip this call.
    if (!isset($_SESSION['session_word'])) {
        $_SESSION['session_word'] = find_session_word();
    }
?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta http-equiv="X-UA-Compatible" contents="IE=edge"> 
		<title>Word Up: Home</title>
		<link rel="stylesheet" href="css/index-stylin.css">

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

        .progress-container {
            width:100%;
            background-color: #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-bar {
            width: 0%;
            background-color: green;
            text-align: center;
            line-height: 30px;
            color: white;
            font-weight: bold;
            transition: width 0.3s ease-in-out;
        }

        #progress-bar-1 {background-color: #4caf50;}
        #progress-bar-2 {background-color: blue;}

        </style>
	</head>

	<body>

    <header>
        <?php include "include/nav.php" ?>
    </header>
	<main>





     <div class="main-section">
        <div class="page-title">
            <h1>WORD <span class="high">UP</span> DASHBOARD</h1>
        </div>
        <!-- <p style="text-align:left; padding-left:200px; width:80%;">Hello <b><?php echo $user_data['user_name']; ?></b>.</p> -->
      
      <!-- Checking if admin user, if so, display notice about new mesages. -->
      <?php if($user_data['admin_user'] === 'Y') { ?>
        <div class="card">
          <h5>You are an admin user.</h5>
            <?php 
              $message_rowcount = check_messages($message_alert); //Call function to notify of new messages
              
              if($message_rowcount > 0) { ?>
                <p class="notice notice-warning">A new message(s) has been submitted. </p>
              <?php }
            ?>
            <form method="POST" action="mark-read.php">
              <input class="btn btn--secondary" type="submit" value="Mark as Read">
            </form>
            
            <!-- Call function to figure work out some stats to display to admin user. -->
            <blockquote>
                <ul>
                  <li>Number of registered users: </li>
                  <li>Number of recorded words: </li>
                  <li>Number of tests taken: </li> 
                </ul>
            </blockquote>
          </div>
      <?php } ?>
   

    <!-- Notification of successful update. -->
    <?php 
      if(isset($_GET['messages-read'])){ 
    ?>
    <div class="alert success">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
      Success: Messages marked as read.
    </div>
    <?php } ?>

          <!-- Check if the user is new, i.e. has no saved vocab. If so, direct them to the input page to get started. -->
          <?php 
              //$rowcount = 0;	//Set to zero for testing.
              if($rowcount == 0) { 
            
            ?>
              <p>It seems that  you haven't added any words to your vocabulary list yet. Why not head over to the <a href="input.php"> Add Vocabulary</a> to get started? </p>
              
              <p>Once you have added some words, you can check out your full list in the <a href="show-data.php">Vocabulary List</a> page.</p>
              
              <p>When you are ready to test your recall on your words, you can do so on the <a href="test.php">Vocabulary Test</a> page.</p>
      </br>
      <p class="notice">By signing up, you have just earned your first <i><b>badge</b></i>. Check it out on the <a href="badges.php">Badges page</a> and earn more by using the site.</p>
      </br>
      <blockquote>One effective way to remember new words is by creating your own personalised vocabulary list. Keep a notebook or digital document where you can put down words you encounter during your language learning journey. Organise the list by categories or themes to make it easier to review and practise regularly.</blockquote>

          <?php	}  else { 
 
            ?>

            <!--  This is the text a visitor will see if they have already used the site prevously. -->
            <p style="text-align:left; padding-left:200px; width:80%;">Hello <b><?php echo $user_data['user_name']; ?></b>. Welcome back to Word <span class="high">Up</span>.</p>
            
        <p style="text-align:left; padding-left:200px; width:80%;">This is your Word <span class="high">Up</span> Dashboard, providing you with a quick overview of your activities and progress on the site. View your recent activities, your stats and achievements or use the quick menu to launch one of the activities.<br>At the bottom, you’ll see the ‘word of the day’. Do you know what today’s word means in your native language?<br>Have fun and continue learning, <i>one word at a time</i>. </p>
 
          <?php } ?>
    </div>

    <div class="cards-wrapper">
        <div class="card">
            <div class="img-container a">
                <img src="images/progress.png" width="100px" alt="">
            </div>
            <h1>PROGRESS & ACHIEVEMENTS</h1>
            <?php 
                if($rowcount > 0) {
            ?>
            <p>Your next word due to be tested is, "<strong><?php echo $next_word; ?></strong>"</p>

            <!-- Progress bar code - mastered words. -->
            <?php

                $mastered_percentage = calc_percentage_mastered($rowcount, $mastered_count);

                $not_tested_percentage = calc_percentage_not_tested($rowcount, $not_tested_count);

            ?>

            <div id="progress-container">   
                <div id="progress-bar-1" class="progress-bar">0%</div>
            </div>% words mastered
            <div id="progress-container">
                <div id="progress-bar-2" class="progress-bar">0%</div>
            </div>% words not tested

            <script>
                let progress1 = 0;
                let progress2 = 0;
                let maxProgress1 = <?php echo $mastered_percentage ?>;
                let maxProgress2 = <?php echo $not_tested_percentage ?>;
                let interval1, interval2;

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
                
                function updateProgress2() {
                    if (progress2 < maxProgress2) {
                        progress2 += 1;
                        document.getElementById("progress-bar-2").style.width = progress2 + "%";
                        document.getElementById("progress-bar-2").innerText = progress2 + "%";
                    } else {
                        clearInterval(interval2);
                    }
                }

                window.onload = function() {

                    //document.getElementById("progress-bar").style.width = progress + "%";
                    //document.getElementById("progress-bar").innerText = progress + "%";

                    interval1 = setInterval(updateProgress1, 50);
                    interval2 = setInterval(updateProgress2, 100);


                };
            </script>




            

            <?php
                } else {
                    echo "<p>Start adding words to see more information here.</p>";
                }//end rowcount check greater than 0 
            ?>


        </div>

        <div class="card">
            <div class="img-container b">
                <img src="images/statistics.png" width="100px" alt="">
            </div>
            <h1>YOUR STATS</h1>
            <?php 
                if($rowcount > 0) {
            ?>
                <p><strong><?php echo $user_data['user_name']; ?></strong>, you have <strong><?php echo $rowcount; ?> </strong>words recorded.</p>
                <p>You have 'mastered' <strong><?php echo $mastered_count ?> </strong>words. </p>
                <p>You have tested <strong><?php echo $tested_count; ?> </strong>words.</p>
                <p>The average duration between adding a word and marking it as <i>mastered</i> is <strong><?php echo $average_to_mastery; ?></strong> days for you. </p>
            <?php } else {
                echo "<p>There are no stats to present yet. </p>";
            }
            ?>
        </div>

        <div class="card">
            <div class="img-container c">
                <img src="images/calendar.png" width="100px" alt="">
            </div>
            <h1>RECENT ACTIVITY</h1>
            <?php
                if($rowcount > 0) {

                    //Call function to get users last 5 words
                    $five_words = last_five_words();

                    //If user has less than 5 words, they will not see recent activity.
                    if (mysqli_num_rows($five_words) < 5) {
                        echo "<h3> Add at least 5 words to see some stats.</h3>";
                    } else {
                        echo "<h3>Your last " . mysqli_num_rows($five_words) . " words:</h3>";   
            ?>
                    <ul class="recent">
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
                } //end if statement checking for 5 rows.
            ?>
            <?php
                    //Call function to get users last 5 mastered
                    $five_mastered = last_five_mastered();

                    if(mysqli_num_rows($five_mastered) < 5) {
                        echo "<h3> Master at least 5 words to see activity.</h3>";

                    } else {
                        echo "<h3>Your last " . mysqli_num_rows($five_mastered) . " words mastered:</h3>";
            ?>
                    <ul class="recent">
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
            <?php
                } else {
                    echo "<p>No recent activity. </p>";
                }//end rowcount check for greater than 0
            ?>
        </div>
    </div>

    <div class="cards-wrapper">
        <div style="width:80%;" class="card">
            <div class="img-container d">
                <img src="images/icon-1.png" width="100px" alt="">
            </div>
            <h1>QUICK MENU</h1>
            <form action="quick-links.php" method="post" class="form-card">
                <div class="buttons-container">
                    <!-- <input type="submit" name="SubmitButton" class="btn btn-secondary"> -->
                    <button style="width:20%;" class="btn btn--secondary" name="AddWordButton" type="submit">ADD A WORD</button><p></p>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <button style="width:20%;" class="btn btn--secondary" name="ViewListButton" type="submit">VIEW LIBRARY</button><p></p>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <button style="width:20%;" class="btn btn--secondary" name="TakeTestButton" type="submit">TAKE A TEST</button><p></p>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <button style="width:20%;" class="btn btn--secondary" name="ViewBadgesButton" type="submit">VIEW BADGES</button><p></p>
                </div>
            </form>
            </div>
        </div>
    </div>
    
    <div class="cards-wrapper">
        <div style="width:80%;" class="card">
            <div class="img-container a">
                <img src="images/word.png" width="100px" alt="">
            </div>
            <h1>WORD OF DAY</h1>
            <!-- <p>Your random word is <strong><i> <?php echo $session_word ?> </i></strong>. </p> -->
            <blockquote>
                <h3>Random word for you:</h3>
                <p style="padding-left:50px;"><strong><i> <?php echo $_SESSION['session_word']; ?> </i></strong></p>
                <p>Do you know what it means?</p>
            </blockquote>

        </div>
        <div style="width:80%;" class="card">
            <div class="img-container a">
                <img src="images/lang-tips.png" width="100px" alt="">
            </div>
            <h1>LANGUAGE TIPS</h1>
            <blockquote>One effective way to remember new words is by creating your own personalised vocabulary list. Keep a notebook or digital document where you can put down words you encounter during your language learning journey. Organise the list by categories or themes to make it easier to review and practise regularly.</blockquote>
        </div>
    </div>

      
		</main>

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>

	</body>
</html>