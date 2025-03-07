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
                3 once unlocked, is held by that user indefinitely. The other 2 trophies can switch between users. The calculations for each are in 'badge-record-functions.php'.

-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
    include("include/file-functions.php");
    include("include/error-logging.php");
    include("include/badge-record-functions.php");
	
    //Declare variables
    $cnt = 0;
    $word = '';
    $test_cnt = 0;
    $rand_word = '';
    $row_count = 0;

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        
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
        #progress-bar-3 {background-color: orange;}


.container{
    display: flex;
    justify-content: center;
    align-items: center;
    display: inline;
}

.container span{
    font-size: 2.5em;
    margin: 0 20px;
}
    .drop-container .drop{
    width: 150px;
    height: 150px;
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    margin: 0 10px;
    position: relative;
    text-shadow: -2px -2px 5px #fff;
    filter: drop-shadow(4px 4px 10px #fff);
    box-shadow: 10px 10px 10px rgba(0,0,0,0.1) inset,
    15px 25px 10px rgba(0,0,0,0.05),
    15px 20px 20px rgba(0,0,0,.05),
    inset -10px -10px 15px rgba(255,255,255,0.9);
    display: inline-block;
}

.drop-container .drop::before{
    content: "";
    width: 10px;
    height: 10px;
    position: absolute;
    left: 20px;
    top: 40px;
    border-radius: 50%;
    background-color: white;
    box-shadow: 0 10px 10px rgba(0,0,0,0.2);
    filter: blur(2px);
}


        .drop p{
    font-size: 1.7em;
}



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
        
        <!-- Section to display cross-site / cross-user badges. -->
        <div style="padding-left: 100px;" class="drop-container">
            <?php 
                $am_i_word_leader = am_i_user_with_most_words();

                if ($am_i_word_leader == 'Y') {
            ?>
                    <div class="drop">
                        <p><i class="fa-solid fa-ranking-star fa-2xl" style="color: #b0f0cf;"></i></p>
                    </div>
                    <span>&nbsp;</span>
            <?php
                }
            ?>
            <?php 
                $am_i_tests_leader = am_i_user_with_most_tests();

                if ($am_i_tests_leader == 'Y') {
            ?>
                    <div class="drop">
                        <p><i class="fa-solid fa-medal fa-2xl" style="color: #b0f0cf;"></i></p>
                    </div>
                    <span>&nbsp;</span>
            <?php
                }
            ?>
            <?php 

                //Check if user has all badges.
                //Putting this check here so that the award will display without user having to visit the 'badges.php' page.
                $badge_number = 14;
                $badge_completion = check_platinum_badge($badge_number);

                if ($badge_completion >= 14) {
                    $am_i_platinum = am_i_platinum_user();

                        if ($am_i_platinum == 'Y') {
            ?>
                    <div class="drop">
                        <p><i class="fa-solid fa-trophy fa-2xl" style="color: #b0f0cf;"></i></p>
                    </div>
                    <span>&nbsp;</span>
            <?php
                    }
                }
            ?>
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

                //Call function to count number of badges awarded to user. Will return a percentage.
                $badge_completion_percentage = calc_overall_badge_completion();

            ?>

            <div id="progress-container">   
                <div id="progress-bar-1" class="progress-bar">0%</div>
            </div>% words mastered
            <div id="progress-container">
                <div id="progress-bar-2" class="progress-bar">0%</div>
            </div>% words not tested
            <div id="progress-container">
                <div id="progress-bar-3" class="progress-bar">0%</div>
            </div>% badges completed

            <script>
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

                window.onload = function() {

                    //document.getElementById("progress-bar").style.width = progress + "%";
                    //document.getElementById("progress-bar").innerText = progress + "%";

                    interval1 = setInterval(updateProgress1, 50);
                    interval2 = setInterval(updateProgress2, 100);
                    interval3 = setInterval(updateProgress3, 75);



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
                <p>Your most tested word category is: <strong> 
                    <?php 
                        echo $most_tested_category = find_most_test_category();
                    ?>
                </strong>.</p>
                <p>Current Steak is: 
                    <?php  
                       echo $current_streak = find_streak(); 
                    ?>
                    days.
                </p>
                <p>Your records streak is: 
                    <?php  
                       echo $longest_streak = find_longest_streak(); 
                    ?>
                    days.
                </p>
                <p>The average duration between adding a word and marking it as <i>mastered</i> is <strong><?php echo $average_to_mastery; ?></strong> days for you. </p>

                <!-- Start of code for test results graph. -->
                <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                <script>
                    // Fetch data from graphs.php
                    fetch('graphs-test-results.php?fetch=true')
                    .then(response => response.json())
                    .then(data => {
                        
                        //Hiding debug code. 
                        /*
                            console.log("Fetched Data:", data); // Debugging output
                            console.log("X-axis values (Dates):", data.x);
                            console.log("Y-axis values (Scores):", data.y);

                            if (!data.x || !data.y || data.x.length === 0 || data.y.length === 0) {
                                console.error("Error: Data arrays are empty.");
                                return;
                            }
                        */
                        var ctx = document.getElementById('myChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.x, // Dates on X-axis
                                datasets: [{
                                    label: 'Your Recent Test Scores',
                                    data: data.y, // Scores on Y-axis
                                    borderColor: 'blue',
                                    fill: false
                                }]
                            },
                            options: {
                                legend: {display: true, "labels": {"fontSize":21,}},
                                scales: {
                               // xAxes: [{ ticks: { fontSize: 16}}],
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error));
                </script>
                <!-- End of code for test results graph. -->

                
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

            <!-- Start of code for test results graph. -->
            <canvas id="myPieChart" style="width:100%;max-width:600px"></canvas>
            
                <script>

                var barColors = [
                "#D53E4F",
                "#F46D43",
                "#FDAE61",
                "#FEE088",
                "#FFFFBF",
                "#E6F596",
                "#ABDDA4",
                "#66C2A5",
                "#3288BD"
                ];
                    // Fetch data from graphs.php
                    fetch('graph-categories.php?fetch=true')
                    .then(response => response.json())
                    .then(data => {
                        
                        //Hiding debug code. 
                        
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
                            options: {
                                legend: {display: true, "labels": {"fontSize":11,}},
                                scales: {
                               // xAxes: [{ ticks: { fontSize: 16}}],
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error));
                </script>
                <!-- End of code for test results graph. -->


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
                <h3>Your last test score: 
            <?php 
                    echo $last_score = get_last_test_score();
            ?>
                %</h3>
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