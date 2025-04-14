<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    This page will allow the user to test their recall of words they have entered into the database. They can choose to test by category
    if they wish. The logic will return words with the lowest 'test' value, i.e. number of times has been selected for testing.

    DETAILS:
    The drop-down field for categories is driven by tb_categories. The same code is used on 'show-data.php' and 'input.php' to retrive categories
    from the DB.
    There is a script at the top of the HTML to work out Levenshtein Distance and a script under that to update the cell on the table with the score. These
    are written in JavaScript.
    There is a check on tb_test_history, to see if the user has a row. If a user doesn't have a row, then they have never used the test function before and 
    a row will be added. A user will only ever have one row on this table. This is used for logic in awarding a badge for user doing their first test.
    The script to reveal the hidden column is written in JavaScript.

    CHANGE HISTORY:
    03-03-25:   Change to the selection SQL to bring back words to test. Added on a qualifier to prevent 'mastered' words from coming up in the test.

    06-03-25:   Added new JavaScript to loop through the cells where the user has entered an answer and it will calculate an overall score. I use this score
                and the number of words tested, and pass these through to 'test-record.php' to update the new table tb_test_record. I am using a form with 
                hidden fields to transfer these variables because the values are calculated in JavaScript (client side) and cannot be used on this PHP
                script (server side). 

    08-03-25:   Changed way the categories drop-down is populated. It now calls a function (populate_category_dropdown) to keep it cleaner. 

    12-03-25:   Adding new function collectData(). This JavaScript function will loop through the HTML table and select the users
                answer and score. The function will use the values from both of these cells, and form up a string such as
                "<input type="hidden" name="word[]" value="Apple">". This these strings will then be added to the div 'hiddenInputs'
                which will allow the passing of these values to 'test-record.php'.

                There is a foreign key relationsip between vocab_id on 'tb_test_words' and id on 'tb_vocab'. When words are chosen to test, it is necessary to 
                send this array of ids to 'test-record.php'. This is achieve by converting the array to a JSON object, converting to a string in the URL and then 
                decoding it on 'test-record.php'.

    14-03-25:   Several changes made to the flow of this page. The concept of a 'hard test' has been introduced. When access via the hard test button on the index.php 
                page, an array of vocab_ids will be passed in via a JSON object. These are ids of words scoring 60% or less in tests. When opening this page via that
                route, the options to choose a category and a number of words are not display. Only the words in the table. When the test is complete the test is reset
                and everything goes back to normal. The variable '$is_hard_test' controls all of these changes and is unset when the test score is saved. 

    15-03-25:   Taking in a second JSON object from test.php filled with the words associated category. It means that if the user decides to not enter a category critera
                in the test, it defaults to 'All' on tb_tests. But with this new JSON object, we can see the correct category for each word in the test on tb_test_word.

    23-03-25:   Added check for rowcount, not showing table if rowcount is 0.

    31-03-25:   Live fix. The UTF-8 encoding is not working correclty so some characters not displaying correctly. I've added a line just before the DB query
                is run to try to enforce UTF-8.

    05-04-25:   Choice of case sensitivity.
-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
    include("include/error-logging.php");

	//Declare variables
	$user_data = check_login($conn); //if logged in, this variable will contain the user data
    $vocab_id_array =[];
    $vocab_category_array = [];
    //$vocab_id_low_scores_array = []; //array for use in populating test.php with the low scoring words.

    $is_hard_test = '';
    $vocab_id_low_scores_array = []; //declare array here but populate at line , could cause error to do here as it may not be populated

    $cnt = 0; 
    $rowcount = count_records($cnt);
    $case_sensitive = '';
?>



<?php
	//Populate the array to fill the category drop-down.
	$result = populate_category_dropdown();

    //Find users preference for case sensitivty during a test.
    $case_sensitive = check_case_sensitivity();
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Word Up: Test</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            //Levenshtein Distance Function
            function levenshteinDistance(a, b) { 
            let m = a.length, n = b.length; 
            let dp = Array.from({ length: m + 1 }, () => Array(n + 1).fill(0)); 
            for (let i = 0; i <= m; i++) dp[i][0] = i; 
            for (let j = 0; j <= n; j++) dp[0][j] = j; 
            for (let i = 1; i <= m; i++) { 
                for (let j = 1; j <= n; j++) { 
                    let cost = a[i - 1] === b[j - 1] ? 0 : 1; 
                    dp[i][j] = Math.min( dp[i - 1][j] + 1, // Deletion 
                    dp[i][j - 1] + 1, // Insertion 
                    dp[i - 1][j - 1] + cost // Substitution
                ); 
                } 
            } 
                return dp[m][n]; 
            } 

            //Function to update Levenshtein distance when exiting cell 1
            function updateDistance(event) {
                let inputField = event.target;   //Get the cell where the event occurred
                let row = inputField.closest("tr");
                let prePopulatedCell = row.cells[3];
                let outputCell = row.cells[2];

                let str1, str2;
                if (<?php echo json_encode($case_sensitive); ?> === 'Y') {
                    str1 = inputField.value.trim();
                    str2 = prePopulatedCell.innerText.trim();
                } else {
                    str1 = inputField.value.trim().toLowerCase();
                    str2 = prePopulatedCell.innerText.trim().toLowerCase();
                }


                if (str1 && str2) {
                    let distance = levenshteinDistance(str1,str2);
                    let maxLength = Math.max(str1.length, str2.length);
                    let similarity = maxLength > 0 ? ((1 - distance / maxLength) * 100).toFixed(2) : 0;
                    outputCell.innerText = similarity + "%";
                    
                    if (similarity >= 80) {
                   //     outputCell.style.backgroundColor = "#28a745";
                   //     outputCell.style.color = "#000000";
                    }
                }
            }
        </script>

		<link rel="stylesheet" href="css/stylin.css">
        <style>
        #saveButton {
            display: none;
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
                <h1>TAKE A TEST</h1>
                <?php 
          if ($rowcount == 0) {
        ?>
          <h4>No test available yet.</h4>
        <?php
          }
        ?>
            </div>
        </div>

        <!-- Notification of successful deletion.  -->
        <?php 
            if(isset($_GET['test-record-updated'])){ 
        ?>
            <div class="alert success">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
            Success. Your test records have been updated.
            </div>
        <?php } ?>


            <p></p>
            <!-- This is the code to display instructions to the user. An accordian field. -->
            <details>
                <summary>ADDITIONAL INFORMATION</summary>
                <p>To do a test, select the category and how many words you wish to test yourself on from the drop-down menus. Press <i>'Submit'</i>. Leaving the <i>category</i> blank will retrieve words from all your categories.</p>
                <p>This will return words that you have tested the least in your chosen category, up to the value you have chosen. </p>
                <p>When you have given an answer to all the words, press <i>'Reveal Answers'</i> to show the words in your native language. </p>
                <p>Words that have been marked as <i>'mastered'</i> will no longer appear in the list to be tested. </p>
                <p>The accuracy score is using <strong>'Levenshtein Distance'</strong>. This distance is a number that tells you how different two strings are. The higher the number, the more different the two strings are.</p>
            </details>
            
            <?php 
                
            //Check to see if test.php was called from the 'Hard Test' button on index.php
            if (isset($_POST['form_identifier'])) {
                $formIdentifier = $_POST['form_identifier'];
        
                if ($formIdentifier === 'HardTest') {
                    $is_hard_test = 'Y';

                    //decode the JSON encode for the vocab_id
                    $vocab_id_low_scores_array = json_decode($_GET['vocab_id'],true);
                }
            } else {
                    $is_hard_test = 'N';
            }
            
            if ($is_hard_test == 'N') {
            ?>
                <!-- Start of form code for Category and number of words drop-downs and submit button. -->
                <div class="card">
                    <div class="custom-select">
                        <form id="testCriteria" class="card-form" method="post">
                            <label class="alternate-input-label" for="dropdown">Choose test options

                            <?php 
                                if ($case_sensitive == 'Y') {
                            ?>
                                <span class="tiny">(You have case sensitivity turned on.)</span>
                            <?php
                                }
                            ?>
                            </label><p></p>
                                <select class="alternate-input-field" name="category" id="category">
                                    <option value="">-- Category --</option>    
                                        <?php
                                            if ($result->num_rows > 0 ) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
                                            }
                                            }
                                        ?>
                                </select>
                                <p></p>
                                <select class="alternate-input-field" name="dropdown" id="dropdown" required>
                                    <option value="">-- Number of words to test --</option>
                                    <!-- <option value="10" <?php echo (isset($_POST['dropdown']) && $_POST['dropdown'] == '10') ? 'selected' : ''; ?>>10</option> -->
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                </select>
                                <div class="alternate-button-wrap">
                                    <button class="alternate-button" type="submit" onclick="checkTestCriteria();">SUBMIT</button>
                                </div>
                        </form>
                    </div>
                </div>
            <?php 
                } //end if statement checking if this is a hard test or not. If Y, won't initally display category selection options 
                else {
                    echo ("<h1>TESTING LOWEST SCORING WORDS</H1>");
                }
            ?>  

        <!-- If rowcount is 0, don't render the table. -->
        <?php 
          if ($rowcount > 0) {
        ?>
            <table id="dataTable" class="hoverTable">
                <thead>
                    <tr>
                        <td>Target Language</td>
                        <td>Answer</td>
                        <td>Accuracy</td>
                        <td>Native Language</td>
                    </tr>
                </thead>
            <tbody>
            <?php 

                $selected_value = 0; //Ensure table is not populate on page load.
                $selected_category = '';

                //Get value from drop-down
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $selected_value = isset($_POST['dropdown']) ? $_POST['dropdown'] : null;
                    $selected_category = isset($_POST['category']) ? $_POST['category'] : null;
                    /*
                    //Check to see if test.php was called from the 'Hard Test' button on index.php
                    if (isset($_POST['form_identifier'])) {
                        $formIdentifier = $_POST['form_identifier'];
                
                        if ($formIdentifier === 'HardTest') {
                            $is_hard_test = 'Y';

                            //decode the JSON encode for the vocab_id
                            $vocab_id_low_scores_array = json_decode($_GET['vocab_id'],true);
                        }
                    } else {
                            $is_hard_test = 'N';
                    }
                    */

                    // if ($selected_value == "-- Amount of words to test --") {
                    //     $selected_value = 0;
                    // }
                }

                // echo ("selected value: ") . $selected_value;
                // echo ("selected category: ") . $selected_category;
                
                if($selected_value > 0 || $is_hard_test == 'Y') {
                //set user_id so we can only see which words the logged in user has entered.
                $user_id = $_SESSION['user_id'];

                //Code to check if tb_test_history has a row and add one if not.
                $sql_test_history = "SELECT * FROM `tb_test_history` WHERE user_id='$user_id' LIMIT 1";
                $run_sql_test_history = mysqli_query($conn, $sql_test_history);

                if(mysqli_num_rows($run_sql_test_history) == 0) {

                    //if number of rows is 0, then add 1 row for this user. Will only ever be 1 row for each user.
                    $sql_upd_history = "INSERT INTO `tb_test_history` (`user_id`) VALUES ('$user_id')";
                    //$run_upd_history = mysqli_query($conn, $sql_upd_history);

                    //Execute SQL and check for errors
                    if (!mysqli_query ($conn, $sql_upd_history)) {
                        echo ("SQL Error: ") . $sql_upd_history . "<br>" . mysqli_error($conn);
                    }
                }
                
                //php code to select from db
                if ($is_hard_test == 'Y') {
                    // Sanitize and convert to a comma-separated string          
                    $ids = array_map('intval', $vocab_id_low_scores_array);
                    $idString = implode(',', $ids);

                    $sql = "SELECT * FROM `tb_vocab` WHERE user_id='$user_id' AND id IN ($idString)";
                } elseif (empty($selected_category) && $is_hard_test == 'N') {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND is_mastered='N' ORDER BY `test_count` ASC limit $selected_value";
                } else {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND category_desc='$selected_category' AND is_mastered='N' ORDER BY `test_count` ASC limit $selected_value";
                }

                // Can't get this if... statement to work.
                if (($selected_value == 0 || $selected_value === NULL || $selected_value == '') && !empty($selected_category)) {
                        $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND category_desc='$selected_category' AND is_mastered='N' ORDER BY `test_count` ASC limit 10";
                }


                // echo ("2nd check of cat: ") . $selected_category;
                // if (empty($selected_category) && ($selected_value = 0)) {
                //     $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND is_mastered='N' ORDER BY `test_count` ASC limit 10";
                //     echo ("I'm here now");
                // }
                /*
                //If a category has been chosen, it needs to be in the SQL select.
                if (empty($selected_category)) {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND is_mastered='N' ORDER BY `test_count` ASC limit $selected_value";
                } else {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' AND category_desc='$selected_category' AND is_mastered='N' ORDER BY `test_count` ASC limit $selected_value";
                }
                */
                // print_r($sql);

                //Trying to enforce UTF-8
                mysqli_set_charset($conn, "utf8mb4");

                $run = mysqli_query($conn, $sql);
              
                if(mysqli_num_rows($run) > 0) {
                    while ($row = $run->fetch_assoc()) {
                        $vocab_id_array[] = $row['id']; //want to pass this to test_record.php
                        $vocab_category_array[] = $row['category_desc'];
                        //$vocab_id_array[] = ['id' => $row['id'], 'category_desc' => $row['category_desc']]; //Populating tb_vocab ids, and categories.
                        $id = $row['id'];
                        $test_cnt = $row['test_count'] + 1;
            ?>
                        <tr>
                            <td><?php echo $row['fr_text'] ?></td>
                            <td><input type="text" class="word-input" name="guess" onblur="updateDistance(event)"></td>
                            <td><input type="text" class="score-input" name="answer" readonly disabled></td>
                            <td><?php echo $row['en_text'] ?></td>
                        </tr>
            <?php
                        //Increment the test_count field on tb_vocab
                        $updateQuery = "UPDATE `tb_vocab` SET `test_count`='$test_cnt' WHERE id = '$id'";
                        $runquery = mysqli_query($conn, $updateQuery);
                    } //close while
                } else {
                    //no words in chosen category
                    ?>
                    <div class="alert warning">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                        You do not have any words in your chosen Category.
                    </div>
                <?php 
                }//close if
            }?>
            </tbody>
            </table>
            <p></p>
            <!-- Add button to reveal hidden column. -->

                <div class="alternate-button-wrap">
                    <!-- <button class="alternate-button" id="viewColumnBtn" onclick="toggleColumn(3); calcOverallScore();" >REVEAL ANSWERS</button>  -->
                    <button class="alternate-button" id="viewColumnBtn" onclick="handleButtonClick();" >REVEAL ANSWERS</button> 
                </div>

            <p>&nbsp;</p>
            <!-- New button to save test score. -->
            <!-- <a href="x" style="width:50%;" class="btn btn-secondary">SAVE SCORE</a> -->

        <?php 
          } //end of check for rowcount.
        ?>


            <?php 
                //If user has not selected a category, default to 'All'
                if (empty($selected_category)) {
                    $selected_category = "All";
                }

            ?>

            <?php

                //print_r($vocab_id_array);
                //convert vocab_id array to JSON
                $json_vocab_id = json_encode($vocab_id_array);
                $json_vocab_cat = json_encode($vocab_category_array);

                //URL encode the JSON
                $encoded_vocab_id = urlencode($json_vocab_id);
                $encoded_vocab_cat = urlencode($json_vocab_cat)
            ?>
            <form id="myForm" action="test-record.php?category=<?php echo $selected_category ?>&id=<?php echo $encoded_vocab_id ?>&cat=<?php echo $encoded_vocab_cat ?>" method="POST">
                <input type="hidden" id="hiddenscore" name="overall_score">
                <input type="hidden" id="hiddennumwords" name="number_words">
                <div id="hiddenInputs"></div> <!-- Hidden inputs will be added here -->
                <div class="alternate-button-wrap">
                    <button id="saveButton" class="alternate-button" type="submit" onclick="collectData();">SAVE RESULTS</button>
                </div>
            </form>

            <!-- This row needs to stay for some reason. -->
            <blockquote><p style="text-align: center; font-size: 4rem;line-height: 1;">TEST SCORE: <span id="overallScore"></span>%</p></blockquote>
   


            <script>
                //This script will automatically press the 'Reveal' button when page is loaded to hide column
             //   window.onload = function() {
             //      setTimeout(function() {
              //          document.getElementById("viewColumnBtn").click(); },0);
              //  };

              window.onload = function() {
                toggleColumn(3); // Change 2 to the column index you want to hide/show
                };
            </script>
        <p></p>

		</main>

        <script>

            function checkTestCriteria() {
               //debugger;

              //  event.preventDefault(); // Prevents immediate form submission
                let value = "<?php echo $selected_value; ?>";

                if (value == "-- Number of words to test --" || value == 0) {
                    Swal.fire({
                        title: "Missing Information",
                        text: "Please select a number of words to test.",
                        icon: "warning",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#3085d6"
                    });
                return; // Stop execution if inputs are empty
                }
                //document.getElementById("testCriteria").submit();
            }
        </script>

<script>

function handleButtonClick() {
   //debugger;
    let isHardTest = "<?php echo $is_hard_test;?>"
    let value = "<?php echo $selected_value; ?>";

    if ((value == 0 || value =="-- Number of words to test --") && isHardTest == 'N') {
        Swal.fire({
            title: "No answers provided.",
            text: "You need to complete the test first.",
            icon: "warning",
            confirmButtonText: "OK",
            confirmButtonColor: "#3085d6"
        });
        return; // Stop execution if inputs are empty
    }

    // Execute the original functions if validation passes
    toggleColumn(3);
    calcOverallScore();
}


            function toggleColumn(colIndex) {
                var table = document.querySelector("table"); // selections all rows in the table
                var rows = table.rows;
                var isHidden = rows[0].cells[colIndex].style.display === "none"; 
                
                // loops through each row and hides (diplay:none) or shows (display:"")
                for (var i = 0; i < rows.length; i++) {
                    rows[i].cells[colIndex].style.display = isHidden ? "": "none";
                }
            }

            //New function to calc overall score
            function calcOverallScore() {
                let table = document.getElementById("dataTable");

                let rows = table.getElementsByTagName("tr");
                let total = 0;
                let count = 0;

                for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header
                    let cellValue = parseFloat(rows[i].cells[2].innerText); // 3rd column (index 2)
                    if (!isNaN(cellValue)) {
                        total += cellValue;
                        count++;
                    }
                }

                let overallScore = count > 0 ? (total / count) : 0; // Average score
                document.getElementById("overallScore").innerText = overallScore.toFixed(2);
                
                //These two values below will be passed to hidden fields in a form to be passed to 'test-record.php'
                document.getElementById("hiddenscore").value = overallScore.toFixed(2);
                document.getElementById("hiddennumwords").value = count;

                if (overallScore > 0) {
                    // Make the save button visible
                    saveButton.style.display = 'inline';
                }
            }


            // Collect data from the table and submit the form
function collectData() {

    
    let table = document.getElementById("dataTable");
    let rows = table.querySelectorAll("tr");
    //let rows = table.getElementsByTagName("tr");


    let hiddenInputs = document.getElementById("hiddenInputs");

    // Clear old hidden inputs
    hiddenInputs.innerHTML = "";

    

    //rows.forEach((row, index) => {
   // Array.from(rows).forEach((row, index) => {
    for (let i = 1; i < rows.length; i++) {

        let cells = rows[i].getElementsByTagName("td");
        if (cells.length >= 3) {

       // debugger;
       // let word = cells[1].querySelector("input") ? cells[1].querySelector("input").value : cells[1].textContent.trim();
        let score = cells[2].querySelector("input") ? cells[2].querySelector("input").value : cells[2].textContent.trim();
        let word = cells[0].querySelector("input") ? cells[0].querySelector("input").value : cells[0].textContent.trim();

        //let word = row.querySelector(".word-input").value;
        //let score = row.querySelector(".score-input").value;

        if (word.trim() !== "") {
            // Create hidden inputs for each word and score
            let wordInput = document.createElement("input");
            wordInput.type = "hidden";
            wordInput.name = "word[]";
            wordInput.value = word;
            hiddenInputs.appendChild(wordInput);

            let scoreInput = document.createElement("input");
            scoreInput.type = "hidden";
            scoreInput.name = "score[]";
            scoreInput.value = score;
            hiddenInputs.appendChild(scoreInput);


        }}
    };

        <?php $is_hard_test = 'N'; 
            // Clear the variable
        unset($formIdentifier);
        ?>

        console.log("Hidden inputs added:", hiddenInputs.innerHTML);
        // Submit the form
        document.getElementById("myForm").submit();

        
}
        </script>




		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
	</body>
</html>