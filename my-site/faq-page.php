
<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 24-02-2025

    HIGHLEVEL DESCRIPTION: 
    Simply an FAQ page for the user to get some information.

    DETAILS:
    This page is using a separate style sheet called 'faq-style.css'. It also contains many of the styles from the 'stylin.css' file to keep it consistent. 
    

    CHANGE HISTORY:

    03-03-25: Added row on FAQ page to explain the concept of mastery. 


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

<div class="wrapper">
      <h1>Frequently Asked Questions</h1>

      <div class="faq">
        <button class="accordion">
          RELEASE NOTES - v0.9
          <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            Date: .......
          </p>
            <p>&nbsp;</p>
          <p>
            This website has gone live in a <strong>BETA</strong> phase so that any kinks can be ironed out before 
            hitting the v1.0 milestone. 
          </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          PRIVACY
        <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            I created this site as a way to help people progress in their language learning journey. Not to gather information. You might have
            noticed that during the sign-up process you were not asked for an e-mail address. So if you are using this site, there is no contact
            information stored on you. There <strong>are no cookies in use</strong> either.</p>
            </p>&nbsp;</p>
            <p>
            The one exception is if you choose to submit a message via the form on the contact page. If you submit a message, the e-mail you 
            provide will be saved in order to respond if needed.
          </p>
        </div>
      </div>

      <div class="faq">
        <button class="accordion">
          NEW FEATURES ROADMAP
          <i class="fa-solid fa-circle-chevron-down"></i>
        </button>
        <div class="pannel">
          <p>
            <strong>Create your own categories. </strong>When you submit a new word, you have to choose between on of the 9 available categories to put that word
            in. Is it a fully comprehensible list? Probably not. The plan is to introduce a feature to allow the user 
            to create their own categories to add to the list. 
          </p>
          <p>&nbsp;</p>
          <p>
            <strong>Word Mastery. </strong>When you start a test on the site, you will be presented with your least tested words. But what if you have fully mastered
            that word and no longer need it to appear in a test? Right now you can delete the word but you probably want to keep your library of words intact. So the
            idea is to introduce the concept of <i>word mastery</i>. Simply mark the words as known and it will no longer appear in a test.  
          </p>
          <p>&nbsp;</p>
          <p>
            <strong>Word Export. </strong>We can't see into the future, so who knows if some unforeseen event takes this site off-line. And with it, the library of
            words you have worked hard to build up and learn. Well, with the introduction of a function to export your library into a file that will be fully compatible
            with almost all other applications and databases, so you won't lose a thing.
          </p>
          <p>&nbsp;</p>
          <p>
            <strong>More fun. </strong>Like the badges, the plan is to add some more functions to highlight achievements of users on the site and maybe introduce some
            friendly competition among you.
          </p>
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
            view in your library and it will not appear in a test while it is marked. 
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
            There are nine badges in total to earn, some of which are more obvious than others. If you want to know how to earn each of the badges,
            keep on reading.
        </p>
        <p>&nbsp;</p>
        <p>
            <ol>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">The description is fairly sefl-explanatory and if you are seeing this page, then you've alredy created your account, thus earning the badge. Well done.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Input your first word to your library for this badge.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">When you input a word, you need to select a 'category' to save the word under. Save a word under each category and you'll have earned this badge.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">After you start your first test, you will earn this badge. Simple.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 25 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 100 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 250 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Add 1,000 words to your library for this one.</span></li>
                <li style="color: rgba(0, 0, 0, 0.7); line-height=1.4; font-size=1.2rem; "><span style="color: black; background: black; span:hover { color: white}">Use the contact form to submit a message, to earn the final badge.</span></li>
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

		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
</body>
</html>