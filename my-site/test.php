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


-->

<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
    include("include/error-logging.php");

	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>



<?php
	//Populate the array to fill the category drop-down.
	$result = populate_category_dropdown();
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Word Up: Test</title>
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

                let str1 = inputField.value.trim();
                let str2 = prePopulatedCell.innerText.trim();


                if (str1 && str2) {
                    let distance = levenshteinDistance(str1,str2);
                    let maxLength = Math.max(str1.length, str2.length);
                    let similarity = maxLength > 0 ? ((1 - distance / maxLength) * 100).toFixed(2) : 0;
                    outputCell.innerText = similarity + "%";
                    
                    if (similarity >= 80) {
                   //     outputCell.style.backgroundColour = "#28a745";
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
                <p>When you have given an answer to all the words, press <i>'Reveal'</i> to show the words in your native language. </p>
                <p>Words that have been marked as <i>'mastered'</i> will no longer appear in the list to be tested. </p>
                <p>The accuracy score is using <strong>'Levenshtein Distance'</strong>. This distance is a number that tells you how different two strings are. The higher the number, the more different the two strings are.</p>
            </details>

            <!-- Start of form code for Category and number of words drop-downs and submit button. -->
            <div class="card">
                <div class="custom-select">
                    <form class="card-form" method="post">
                        <label class="new-input-label" for="dropdown">Choose Category & number of words.</label><p></p>
                            <select class="new-input-field" name="category" id="category">
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
                            <select class="new-input-field" name="dropdown" id="dropdown">
                                <option>-- Amount of words to test -- </option>
                                <!-- <option value="10" <?php echo (isset($_POST['dropdown']) && $_POST['dropdown'] == '10') ? 'selected' : ''; ?>>10</option> -->
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                            </select>
                            <div class="action">
                                <input style="width:100%;" class="btn btn--secondary" type="submit" value="SUBMIT">
                            </div>
                    </form>
                </div>
            </div>
                                
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

                //Get value from drop-down
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $selected_value = $_POST['dropdown'];
                    $selected_category = $_POST['category'];
                }

                
                if($selected_value > 0) {
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
                //If a category has been chosen, it needs to be in the SQL select.
                if (empty($selected_category)) {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and is_mastered='N' ORDER BY `test_count` ASC limit $selected_value";
                } else {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and category_desc='$selected_category' and is_mastered='N' ORDER BY `test_count` ASC limit $selected_value";
                }


                $run = mysqli_query($conn, $sql);

                if(mysqli_num_rows($run) > 0) {
                    while ($row = $run->fetch_assoc()) {
                        $id = $row['id'];
                        $test_cnt = $row['test_count'] + 1;
            ?>
                        <tr>
                            <td><?php echo $row['fr_text'] ?></td>
                            <td><input type="text" name="guess" onblur="updateDistance(event)"></td>
                            <td>0%</td>
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
            <button style="width:100%;" class="btn btn--secondary" id="viewColumnBtn" onclick="toggleColumn(3); calcOverallScore();">REVEAL ANSWERS</button> 

            <p>&nbsp;</p>
            <!-- New button to save test score. -->
            <!-- <a href="x" style="width:50%;" class="btn btn-secondary">SAVE SCORE</a> -->

            
            <?php 
                //If user has not selected a category, default to 'All'
                if (empty($selected_category)) {
                    $selected_category = "All";
                }
            ?>

            <form id="myForm" action="test-record.php?category=<?php echo $selected_category ?>" method="POST">
                <input type="hidden" id="hiddenscore" name="overall_score">
                <input type="hidden" id="hiddennumwords" name="number_words">

                <button id="saveButton" class="btn" type="submit">SAVE RESULTS</button>
            </form>

            <!-- This row needs to stay for some reason. -->
            <p style="text-align: center; font-size: 4rem;line-height: 1;">TEST SCORE: <span id="overallScore"></span>%</p>

            <script>
                //This script will automatically press the 'Reveal' button when page is loaded to hide column
                window.onload = function() {
                    setTimeout(function() {
                        document.getElementById("viewColumnBtn").click(); },0);
                };
            </script>
        <p></p>

		</main>

        <script>
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


        </script>




		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
	</body>
</html>