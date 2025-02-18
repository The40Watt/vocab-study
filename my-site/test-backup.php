<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Test</title>
		<link rel="stylesheet" href="css/simple.css">
        <style>
            .custom-select {
            min-width: 350px;
            position: relative;
            }

            .custom-select select {
            appearance: none;
            width: 50%;
            font-size: 1.15rem;
            padding: 0.675em 6em 0.675em 1em;
            background-color: #fff;
            border: 1px solid #caced1;
            border-radius: 0.25rem;
            color: #000;
            cursor: pointer;
            }
        </style>
	</head>

	
	
	<body>

		<header>

		<?php include "include/nav.php" ?>
            
			<h1>Test</h1>
		</header>

		<main>

        <div class="custom-select">
            <form method="post">
                <label for="dropdown">How many words do you want to review?</label>
                    <select name="dropdown" id="dropdown">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                    <input type="submit" value="Submit">
            </form>
        </div>

            <table class="hoverTable">
                <thead>
                    <tr>
                        <td>French Text</td>
                        <td>Answer</td>
                        <td>English Text</td>
                    </tr>
                </thead>
            <tbody>
            <?php 

            $selected_value = 0;
                //Get value from drop-down
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $selected_value = $_POST['dropdown'];
                    echo $selected_value;
                }
                if($selected_value > 0) {
                //set user_id so we can only see which words the logged in user has entered.
                $user_id = $_SESSION['user_id'];

                //php code to select from db
                $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY `test_count` ASC limit $selected_value";
                $run = mysqli_query($conn, $sql);

                if(mysqli_num_rows($run) > 0) {
                    while ($row = $run->fetch_assoc()) {
                        $id = $row['id'];
                        $test_cnt = $row['test_count'] + 1;
            ?>
                        <tr>
                            <td><?php echo $row['fr_text'] ?></td>
                            <td><input type="text" name="guess"></td>
                            <td><?php echo $row['en_text'] ?></td>
                        </tr>
            <?php
                        //Update query
                        $updateQuery = "UPDATE `tb_vocab` SET `test_count`='$test_cnt' WHERE id = '$id'";
                        $runquery = mysqli_query($conn, $updateQuery);
                    } //close while
                } //close if
            }?>
            </tbody>
        </table>

                <button id="viewColumnBtn" onclick="toggleColumn(2)">Reveal</button> 
                
                <script>
                    //This script will automatically press the 'Reveal' button when page is loaded to hide column
                    window.onload = function() {
                        setTimeout(function() {
                            document.getElementById("viewColumnBtn").click(); },0);
                    };
                </script>

            <!-- Add button to reveal translation -->
            <form method="post">
                <button class="action-button" name="UpdateTestNum" type="submit">Finished</button>
            </form>

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
        </script>
		<footer>
			<p>Made by me.</p>
		</footer>
	</body>
</html>