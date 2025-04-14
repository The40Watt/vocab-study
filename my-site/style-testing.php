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

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <link rel="stylesheet" href="css/stylin.css">

    <title>Document</title>
    <style>

    </style>
</head>
<body>
    <header>
		<?php include "include/nav.php" ?>
		    
	</header>
<main>
<h1><?php echo $user_data['user_name']; ?>'s Homepage</h1>

<h3>Testing Details style</h3>

<details class="summary">
                <summary>Additional info.</summary>
                <p>To do a test, select the category and how many words you wish to test yourself on from the drop-down menus. Press <i>'Submit'</i>. Leaving the <i>category</i> blank will retrieve words from all your categories.</p>
                <p>This will return words that you have tested the least in your chosen category, up to the value you have chosen. </p>
                <p>When you have given an answer to all the words, press <i>'Reveal'</i> to show the words in your native language. </p>
                <p>The accuracy score is using <strong>'Levenshtein Distance'</strong>. This distance is a number that tells you how different two strings are. The higher the number, the more different the two strings are.</p>
            </details>

<h3>Testing Notice used on the site</h3>
<p class="notice position1"> Notice using position 1</p>
<p class="notice position2"> Notice using position 2</p>
<p class="notice notice-warning">Some text in here for display purposes.</p>

<h3>Adding a form to see how it looks</h3>
<form action="">
    <label for="dropdown">Choose a Category and how many words to test:</label>
        <select name="category" id="category">
            <option value="">-- Fake Value --</option>    
        </select>
        <select name="dropdown" id="dropdown">
            <option>-- Fake Value -- </option>
        </select><br>
        <input type="text" name="en_text" class="form-control" value="">
        <input type="submit" value="Submit">
</form>

<h3>Testing Blockquote</h3>
<blockquote>This is a blockquote.</blockquote>

<h3>Testing Alerts used on the site</h3>
    <div class="alert warning">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        This is a warning message.
    </div>
    <div class="alert success">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        This is a success message.
    </div>
    <div class="alert error">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        This is an error message.
    </div>


    <h3>Testing Tables - HOVERTABLE</h3>
    <table class="hoverTable">
                <thead>
                    <tr>
                        <td>Target Language</td>
                        <td>Answer</td>
                        <td>Accuracy</td>
                        <td>Native Language</td>
                    </tr>
                </thead>
            <tbody>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            </tbody>
</table>

<h3>Testing Tables</h3>
    <table >
                <thead>
                    <tr>
                        <td>Target Language</td>
                        <td>Answer</td>
                        <td>Accuracy</td>
                        <td>Native Language</td>
                    </tr>
                </thead>
            <tbody>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            <tr>
                <td>asdf</td><td>asdf</td><td>0adfa</td><td>asdfasf</td>
            </tr>
            </tbody>
</table>



<h3>Testing 'Div' tags used on badge page.</h3>
<div class="main_container">
        <!-- This is the 1st badge. Awarded for creating an account. -->
        <div class="product">
            <img src="images/new_account.png" alt="">
            <div class="product_desc">
                <h3>Signed-Up</h3>
                <h6>Awarded: </h6>
            </div>
        </div>
        <!-- This is the 1st badge. Awarded for creating an account. -->
        <div class="product">
            <img src="images/new_account.png" alt="">
            <div class="product_desc">
                <h3>Signed-Up</h3>
                <h6>Awarded: </h6>
            </div>
        </div>
        <!-- This is the 1st badge. Awarded for creating an account. -->
        <div class="product">
            <img src="images/new_account.png" alt="">
            <div class="product_desc">
                <h3>Signed-Up</h3>
                <h6>Awarded: </h6>
            </div>
        </div>
</div>

<h3>Testing other fields</h3>
<div class="">
			<form method="POST" action="contact-form.php" >
				<h2 >Contact</h2>
				<p><label>Name: </label><input name="name" type="text" id="name" required/></p>
				<p><label>Email: </label><input name="email" type="email" id="email" required/></p>
				<p><label>Subject: </label><input name="subject" type="text" id="subject" required/></p>
				<p></label>Message: </label><textarea name="message" id="message" required></textarea></p>
				<p><input type="submit" value="Send" /></p>
			</form>
			</div>


<h3> Testing chunky buttons</h3>
<form>
    <input class="btn" type="submit">
    <button class="btn btn--secondary">Accept</button>
    <button class="btn btn--small">Cancel</button>
</form>

<h3> Testing other style buttons</h3>
<div class="action">
    <form>
        <input class="btn" type="submit">
        <button class="action-button">Accept</button>
        <button class="action-button">Cancel</button>
    </form>
</div>

<h3> Testing mix of two styles </h3>
<div class="card">
    <div class="card-image">
        <h2 class="card-heading"> Heading for form</h2>
    </div>
            <form class="card-form">
                <div class="input">
                    <input type="text" class="input-field" name="fr_text" required/>
                    <label class="input-label">Target Lanuage Text</label>
                </div>
                <div class="input">
                    <input type="text" class="input-field" name="en_text" required/>
                    <label class="input-label">Native Language Text</label>
                </div>
                <div class="action">
                    <button class="btn btn--secondary" name="SubmitButton" type="submit">Add Vocabulary</button>
                </div>
            </form>
</div>

<h3> OPTOIN 1 FOR CONTACT FORM</h3>
<div class="card">
    <div class="card-image">
        <h2 class="card-heading"> Heading for form</h2>
    </div>
            <form class="card-form">
                <div class="input">
                    <input type="text" class="input-field" name="fr_text" required/>
                    <label class="input-label">Name</label>
                </div>
                <div class="input">
                    <input type="text" class="input-field" name="en_text" required/>
                    <label class="input-label">E-Mail</label>
                </div>
                <div class="input">
                    <input type="text" class="input-field" name="fr_text" required/>
                    <label class="input-label">Subject</label>
                </div>
                <div class="input">
                    <textarea type="text" class="input-field" name="fr_text" required></textarea>
                    <label class="input-label">Message</label>
                </div>
                <div class="action">
                    <button class="btn btn--secondary" name="SubmitButton" type="submit">SEND MESSAGE</button>
                </div>
            </form>
</div>

        <h3> OPTION 2 FOR ADD VOCAB FORM. </h3>
        <div class="card">
            <form >
                <div class="input">
                    <select class="new-input-field">
                        <option> -- Category -- </option>
                    </select>
                </div>
                <div class="input">
                    <input class="new-input-field" type="text" name="fr_text" required/>
                    <label class="new-input-label">Target Language Text</label>
                </div>
                <div class="input">
                    <input class="new-input-field" type="text" name="en_text" required/>
                    <label class="new-input-label">Native Language Text</label>
                </div><br>
                <div class="action">
                    <button class="btn btn--secondary" name="SubmitButton" type="submit">ADD VOCABULARY</button>
                </div>
            </form>
        </div>

        <h3> OPTION 2 FOR CONTACT FORM </h3>
        <div class="card">
            <form >
                <div class="input">
                    <input class="new-input-field" type="text" name="fr_text" required/>
                    <label class="new-input-label">Name</label>
                </div>
                <div class="input">
                    <input class="new-input-field" type="text" name="en_text" required/>
                    <label class="new-input-label">E-mail</label>
                </div>
                <div class="input">
                    <input class="new-input-field" type="text" name="fr_text" required/>
                    <label class="new-input-label">Subject</label>
                </div>
                <div class="input">
                    <textarea class="new-input-field" type="text" name="fr_text" required></textarea>
                    <label class="new-input-label">Message</label>
                </div>
                <br>
                <div class="action">
                    <button class="btn btn--secondary" name="SubmitButton" type="submit">SEND MESSAGE</button>
                </div>
            </form>
        </div>
</main>


	<!-- Add the footer. -->
	 <?php include "include/footer.php" ?>

   <!-- <footer>

<div class="footerContainer">
	<div class="socialIcons">
		<a href="" ><i class="fa-brands fa-facebook"></i></a>
	</div>
	<div class="footerNav">
		<ul>
			<li><a href="" >Home</a></li>
			<li><a href="" >News</a></li>
			<li><a href="" >Stuff</a></li>
			<li><a href="" >More</a></li>
			<li><a href="" >Anything</a></li>
		</ul>
	</div>
	<div class="footerBottom">
		<p>some text here.</p>
	</div>
</div>
		</footer> -->
    
</body>
</html>