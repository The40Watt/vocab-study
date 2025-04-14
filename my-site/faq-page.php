
<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 24-02-2025

    HIGHLEVEL DESCRIPTION: 
    Simply an FAQ page for the user to get some information.

    DETAILS:
    This page is using a separate style sheet called 'faq-style.css'. It also contains many of the styles from the 'stylin.css' file to keep it consistent. 
    

    CHANGE HISTORY:

    03-03-25: Added row on FAQ page to explain the concept of mastery. 

    04-04-25: Updated the text on some entries. 


-->
<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/faq-style.css">
    <title>Word Up: FAQ</title>
</head>
<body>

		<header>
    		<?php include "include/nav.php" ?>
		</header>
<main>
    <div class="main-section">
        <div class="page-title">
            <h1>FREQUENTLY ASKED QUESTIONS</h1>
        </div>
    </div>

<div class="wrapper">
      <div class="faq">
        <button class="accordion">
          RELEASE NOTES - v0.9
          <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            Date: 5th April 2025
          </p>
            <p>&nbsp;</p>
          <p>This website has gone live in a <strong>BETA</strong> phase so that any kinks can be ironed out before hitting the v1.0 milestone.</p>
          <p>&nbsp;</p>
          <p>As the site has just gone live, all users will be registered as Premium users upon registration so they can enjoy all the features of the site. This free period will last approx. one month.</p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          PREMIUM
          <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>There are 4 tabs of information available on the users dashboard. The <i>Summary Details</i> tab is always available but the other 3 (Progress & Achievements, Recent Activity and Testing Details) which provide some more in-depth information
          are only available once a donation has been made on the <strong>Ko-Fi</strong> page for Word Up. A payment of €1, will unlock all features for a month. The need for a subscription is
          only driven by the need to cover the monthly website server / hosting costs.</p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          PRIVACY
          <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            I created this site as a way to help people progress in their language learning journey. Not to gather information. On signing up you provided your email address to be used for verification purposes if you were to forget your password, nothing else. There are no newsletters here. Nor any cookies, delicious as they sound.
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          WORD MASTERTY?
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            As you continue your language learning journey you will pick up more and more vocabulary every day. As you continue to see these words, you will learn them
            well enough that you no longer need to see and practice them. That is where the concept of <strong>Mastery</strong> comes in. 
          </p>
          <p>&nbsp;</p>
          <p>
            You can mark a word as <i>mastered</i> in your vocabularly library, or unmark it if needed. Once it is marked, you can choose to filter it out of your list 
            view in your library and it will not appear in a test while it is marked as mastered. 
          </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          ORGANISING MY WORDS?
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            When you start building your library of words, there will be 9 default categories available to chooose from. By using the 
            options provided in the <strong>User Preferences</strong> page you will be able to create, edit and delete<span style="line-height:30px;vertical-align:middle" >*</span> categories as you
            like. 
          </p>
          <p>&nbsp;</p>
          <p>
          <span style="line-height:30px;vertical-align:middle" >*</span>If you delete a category that has associated words, these words will then become 
          orphaned, i.e. you can no longer filter them by category. This can be resolved by moving these words to a new category. This can be done in
          the 'User Preferences' page.
          </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          TAKE YOUR LIBRARY WITH YOU?
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
          Worried that you'll put time into building your library only for this site to disappear? Worry not. In the <i>'User Preferences'</i> page you will see an option to 
          download all the words in your library. This will put your words into a <i>'.csv'</i> file, an industry standard format that will allow you to take your library with 
          you for use in other applications if you wish.        
         </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          I HAVE A NEW TAB ON MY DASHBOARD?
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            Firstly, congratulations. This new tab denotes users who have completed specific achievements among all users of the site. There is an award for the 
            user with the most words in their library and an award for the user with the most completed tests. The third award is the most unique in that it 
            can only be awarded to one user. Ever. The first user to unlock all the <strong>Badges</strong> will unlock this unique achievement.
          </p>
          <p>&nbsp;<p>
          <p><i class="fa-solid fa-crown fa-2xs" style="color:rgb(6, 6, 6);"></i>  | The user with most words.</p>
          <p><i class="fa-solid fa-trophy fa-2xs" style="color:rgb(6, 6, 6);"></i> | The user with most completed tests.</p>
          <p><i class="fa-solid fa-gem fa-2xs" style="color:rgb(6, 6, 6);"></i>  | The user to complete all badges first.</p>
          </div>
      </div>

      <div class="faq">
        <button class="accordion">
          WHY SET A TARGET LANGUAGE?
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            In the <strong>User Preferences</strong> section, you have the option to set a <i>Target Language</i>. By doing so, you will be able to get a new random word 
            in your target language every time you log in. This is not available for all languages at the moment. 
          </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          KO-FI?
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            <strong>Ko-Fi</strong> is a website that creators use to accept donations and subscriptions from fans. The reason I have created 
            this Ko-Fi account is to help pay for monthly website server / hostings costs. That is all.
          </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
            BLUEKSKY
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            Why <strong>Bluesky</strong>? Because Twitter is for fascists. 
          </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          BADGES HELP
          <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            There are 14 badges in total to earn, some of which are more obvious than others. If you want to know how to earn each of the badges,
            keep on reading.
        </p>
        <p>&nbsp;</p>
        <p>
            <ol>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">The description is fairly self-explanatory and if you are seeing this page, then you've alredy created your account, thus earning the badge. Well done.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Input your first word to your library for this badge.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">When you input a word, you need to select a 'category' to save the word under. Save a word under each category and you'll have earned this badge.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">After you start your first test, you will earn this badge. Simple.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 25 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 100 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 250 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 1,000 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Use the contact form to submit a message.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Use the mastery functionality to mark your first word as mastered.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Use the mastery function to mark 25 words as mastered.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Use the mastery function to mark 100 words as mastered.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Use the mastery function to mark 250 words as mastered.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Earn all the other badges to unlock this final badge. Superstar!</span></li>
            </ol>
          </p>
        </div>
      </div>

    </div>

    <p></p>

    <script>
        //Select all elements will class 'accordion', store as array in 'acc'
        var acc = document.getElementsByClassName
        ("accordion");

        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active"); //toggle means add 'active' class if not there, remove if there
                this.parentElement.classList.toggle("active"); //will add 'active' class to parent of current class

                var panel = this.nextElementSibling; 

                if (panel.style.display === "block") {
                        panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }

    </script>
  </main>
		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
</body>
</html>