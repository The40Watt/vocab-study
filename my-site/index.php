<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data
	$rowcount = count_records($cnt);
	$next_word = next_word($word);
	$tested_count = number_of_tests($test_cnt);

?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Stephen's Homepage</title>
		<link rel="stylesheet" href="css/simple.css">
		<style>
			        .notice {
  background-color: #EFEFEF;
  border: 3px solid #444;
  padding: 1rem;
  margin: 2rem 0;
  width: 75%;
  text-align: left;
  position: relative;
  left: 40;

}

.notice2 {
  background-color: #EFEFEF;
  border: 3px solid #444;
  padding: 1rem;
  margin: 2rem 0;
  width: 75%;
  text-align: left;
  position: relative;
  left: 200px;
	
}

.notice3 {
  background-color: #EFEFEF;
  border: 3px solid #444;
  padding: 1rem;
  margin: 2rem 0;
  width: 75%;
  text-align: left;
  position: relative;
  left:80px;
	
}

.notice::before {
  content: "INFO";
  background: #AACCFF;
  width: 5rem;
  border-right: 3px solid #444;
  border-bottom: 3px solid #444;
  border-left: 3px solid #444;
  border-top: 3px solid #444;
  display: block;
  text-align: center;
  position: relative;
  left: 1rem;
  top: -2rem;
  padding: 2px 10px;
  font-weight: bold;
}

.notice2::before {
  content: "INFO";
  background: #AACCFF;
  width: 5rem;
  border-right: 3px solid #444;
  border-bottom: 3px solid #444;
  border-left: 3px solid #444;
  border-top: 3px solid #444;
  display: block;
  text-align: center;
  position: relative;
  left: 1rem;
  top: -2rem;
  padding: 2px 10px;
  font-weight: bold;
}

.notice3::before {
  content: "INFO";
  background: #AACCFF;
  width: 5rem;
  border-right: 3px solid #444;
  border-bottom: 3px solid #444;
  border-left: 3px solid #444;
  border-top: 3px solid #444;
  display: block;
  text-align: center;
  position: relative;
  left: 1rem;
  top: -2rem;
  padding: 2px 10px;
  font-weight: bold;
}

.warning {
  background-color: #EFEFEF;
  border: 3px solid #444;
  padding: 1rem;
  margin: 2rem 0;
  width: 75%;
  text-align: left;
  position: relative;
  left: 40;

}

.warning::before {
  content: "NEW MESSAGE";
  background:rgb(233, 28, 28);
  width: 20rem;
  border-right: 3px solid #444;
  border-bottom: 3px solid #444;
  border-left: 3px solid #444;
  border-top: 3px solid #444;
  display: block;
  text-align: center;
  position: relative;
  left: 1rem;
  top: -2rem;
  padding: 2px 10px;
  font-weight: bold;
}
</style>
	</head>

	
	
	<body>

		<header>
			<?php include "include/nav.php" ?>
				<h1><?php echo $user_data['user_name']; ?>'s Homepage</h1>
			</header>
		<main>


			<p>Hello <b><?php echo $user_data['user_name']; ?></b>.</p> 

      <!-- Checking if admin user, if so, display notice about new mesages. -->
      <?php if($user_data['admin_user'] === 'Y') { ?>
        <h6>You are an admin user.</h6>
          <?php 
            $message_rowcount = check_messages($message_alert); //Call function to notify of new messages
            
            if($message_rowcount > 0) { ?>
              <p class="warning">A new message(s) has been submitted. </p>
            <?php }
          ?>
      <?php } ?>

			<!-- Check if the user is new, i.e. has no saved vocab. If so, direct them to the input page to get started. -->
			<?php 
				//$rowcount = 0;	//Set to zero for testing.
				if($rowcount == 0) { ?>
				<p>It seems that  you haven't added any words to your vocabulary list yet. Why not head over to the <a href="input.php"> Add Vocabulary</a> to get started? </p>
				
				<p>Once you have added some words, you can check out your full list in the <a href="show-data.php">Vocabulary List</a> page.</p>
				
				<p>When you are ready to test your recall on your words, you can do so on the <a href="test.php">Vocabulary Test</a> page.</p>

        <p class="notice">By signing up, you have just earned your first <i><b>badge</b></i>. Check it out on the <a href="badges.php">Badges page</a> and earn more by using the site.</p>
				
			<?php	}  else { ?>

			<!--  This is the text a visitor will see if they have already used the site prevously. -->
			<p>Welcome back. Below you'll see some <i>facts & figures</i> to hopefully offer you some encouragement to keep going. So go add some new words and remember to keep on testing.</p>
			
			<blockquote>One effective way to remember new words is by creating your own personalised vocabulary list. </blockquote>
			
			<section>
				<p class="notice"><strong><?php echo $user_data['user_name']; ?></strong>, you have <strong><?php echo $rowcount; ?> </strong>words recorded. See your full list <a href="show-data.php">here.</a></p>
				<p class="notice2">Your next word due to be tested is <strong><?php echo $next_word; ?></strong> </p>
				<p class="notice3">You have tested <strong><?php echo $tested_count; ?> </strong>words.</p>

			</section>
			<?php } ?>
		</main>

		<?php include "include/footer.php" ?>

	</body>
</html>