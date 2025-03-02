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


-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
    include("include/file-functions.php");

    //this will ensure PHP displays all errors
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
    //Declare variables
    $cnt = 0;
    $word = '';
    $test_cnt = 0;
    $rand_word = '';

	$user_data = check_login($conn); //if logged in, this variable will contain the user data
	$rowcount = count_records($cnt);
    $next_word = next_word($word);
	$tested_count = number_of_tests($test_cnt);

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
              if($rowcount == 0) { ?>
              <p>It seems that  you haven't added any words to your vocabulary list yet. Why not head over to the <a href="input.php"> Add Vocabulary</a> to get started? </p>
              
              <p>Once you have added some words, you can check out your full list in the <a href="show-data.php">Vocabulary List</a> page.</p>
              
              <p>When you are ready to test your recall on your words, you can do so on the <a href="test.php">Vocabulary Test</a> page.</p>
      </br>
      <p class="notice">By signing up, you have just earned your first <i><b>badge</b></i>. Check it out on the <a href="badges.php">Badges page</a> and earn more by using the site.</p>
      </br>
      <blockquote>One effective way to remember new words is by creating your own personalised vocabulary list. Keep a notebook or digital document where you can put down words you encounter during your language learning journey. Organise the list by categories or themes to make it easier to review and practise regularly.</blockquote>

          <?php	}  else { ?>

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
            <p>Your next word due to be tested is, "<strong><?php echo $next_word; ?></strong>"</p>

            <?php
                $php_var_percent = 80;
            ?>

            <div id="myProgress">
                <div id="myBar"><?php echo $php_var_percent . "%"?></div>
                <p style="text-transform: uppercase;">% words tested</p>
          </div>
      


          <script>
                
                var js_var_percent = <?php echo json_encode($php_var_percent); ?>;
                var i = 0;
                window.onload = function move() {
                if (i == 0) {
                    i = 1;
                    var elem = document.getElementById("myBar");
                    var width = 1;
                    var id = setInterval(frame, 10);
                    function frame() {
                    if (width >= js_var_percent) {
                        clearInterval(id);
                        i = 0;
                    } else {
                        width++;
                        elem.style.width = width + "%";
                    }
                    }
                }
                }
        </script>



        </div>

        <div class="card">
            <div class="img-container b">
                <img src="images/statistics.png" width="100px" alt="">
            </div>
            <h1>YOUR STATS</h1>
            <p><strong><?php echo $user_data['user_name']; ?></strong>, you have <strong><?php echo $rowcount; ?> </strong>words recorded.</p>
            <p>You have tested <strong><?php echo $tested_count; ?> </strong>words.</p>
        </div>

        <div class="card">
            <div class="img-container c">
                <img src="images/calendar.png" width="100px" alt="">
            </div>
            <h1>RECENT ACTIVITY</h1>
            <?php
                //Call functionto get users last 5 words
                $five_words = last_five_words();

                echo "<h3>Your last " . mysqli_num_rows($five_words) . " words:</h3>";
            ?>
                <ul>
            <?php  
                while ($new_row = $five_words->fetch_assoc()) { 
                ?>
                <!-- Cycle through array of 5 words. -->
                <!--<p><?php echo $new_row["fr_text"]; ?> </p> -->
                <li><?php echo $new_row["fr_text"]; ?> </li>
            <?php 
                }
            ?>
            </ul>
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