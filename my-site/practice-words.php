<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 08-04-2025

    HIGHLEVEL DESCRIPTION: 
    This page allows the user to practice memorising their words. 

    DETAILS:
    This page calls 'process-practice.php' when the filter button is pressed.
    It will do a select on 'tb_vocab' to return words based on category. No mastered words are returned.
    The cards and button display are controlled by Javascript. 

    CHANGE HISTORY:

    10-04-25:   LIVE FIX. Adding extra piece of Javascript and changing .css to help the flip mechanic work smoother on mobile.


-->
                

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();


	include("include/connection.php");
	include("include/functions.php");
	include("include/error-logging.php");
    include("include/badge-record-functions.php");

    //Declare variables
    $cnt = 0;
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

    //Populate the array for the category drop-down menu
    $result = populate_category_dropdown();

    //Checking if user has words
    $rowcount = count_records($cnt);


?>

<?php

    $practice_array = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include 'process-practice.php'; // this file fills $results_array

        //Populate the array for the category drop-down menu
        $result = populate_category_dropdown();
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Up: Practice</title>
    <link rel="stylesheet" href="css/stylin.css">
</head>



<body>

<header>
  <?php include "include/nav.php" ?>
</header>

<main>



    <!-- Notification of no words in category.  -->
    <?php 
        if(isset($_GET['no-words'])){ 
    ?>
        <div class="alert warning">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        Warning. There are no words in your chosen category.
        </div>
        
        <script>
            //Some javascript to remove '?no-words' from the URL otherwise the message keeps popping up when the filter button is pressed.
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({path: cleanUrl}, '', cleanUrl);
        </script>
    <?php } ?>


    <!-- Page title code -->
    <div class="main-section">
		<div class="page-title">
			<h1>PRACTICE MAKES PERFECT</h1>
        <?php 
          if ($rowcount == 0) {
        ?>
          <h4>Looks like you not added any words yet :-(</h4>
        <?php
          }
        ?>
		</div>
  	</div>

    <div class="practice-outer">
        <!-- Form at top of the page code. -->
        <div class="card">
            <form method="POST" class="card-form">
                <label for="category" class="alternate-input-label">Choose a category.</label>
                <select name="category" id="category" class="alternate-input-field" autofocus>
                    <option value="">-- Category --</option>    
                    <?php
                        if ($result->num_rows > 0 ) {
                            while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
                            }
                        }
                    ?>
                </select>
                    <div class="alternate-button-wrap">
                        <button class="alternate-button" name="SubmitButton" type="submit">FILTER WORDS</button>
                    </div>
            </form>
        </div>
        
        <!-- Start of card code -->
        <div class="flipcontainer" id="flashcard">
            <div class="flipcard">
                <div class="front" id="card-front"></div>
            <div class="back" id="card-back">
            </div>
            </div>
        </div>

        <div class="alternate-button-wrap">
            <button class="alternate-button" id="prevButton">Previous Word</button>
            <button class="alternate-button" id="nextButton">Next Word</button>
        </div>
    </div> <!-- end practice-outer -->


    <script>
    // Convert the PHP array to JavaScript
    let words = <?php echo json_encode($practice_array); ?>;

    let currentIndex = 0;

    // Function to display current word
    function showWord(index) {
        if (index < words.length) {
            document.getElementById('card-front').innerText = words[index]['fr_text'];
            document.getElementById('card-back').innerText = words[index]['en_text'];
            
            // Show or hide buttons depending on position
            document.getElementById('prevButton').disabled = (index === 0);
            document.getElementById('nextButton').disabled = (index === words.length - 1);
        } else {
            document.getElementById('flashcard').innerHTML = "";
            document.getElementById('nextButton').style.display = 'none';
            document.getElementById('prevButton').style.display = 'none';
        }
    }

    // Button click event
    document.getElementById('nextButton').addEventListener('click', function() {
        currentIndex++;
        showWord(currentIndex);
    });

    // Previous button click
    document.getElementById('prevButton').addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            showWord(currentIndex);
        }
    });

    // Show the first word immediately
    showWord(currentIndex);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flipContainer = document.querySelector('.flipcontainer');

        flipContainer.addEventListener('click', function() {
            flipContainer.classList.toggle('flipped');
        });
    });
</script>

</main>


    <!-- Add the footer. -->
    <?php include "include/footer.php" ?>
</body>
</html>