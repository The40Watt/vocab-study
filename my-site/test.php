<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	//$_SESSION;

	include("include/connection.php");
	include("include/functions.php");
	
	$user_data = check_login($conn); //if logged in, this variable will contain the user data

?>

<?php

	//-------------------
  // Function to select categories from the codes table. 
  //-------------------
	$sql = "SELECT * FROM `ct_categories`";
	$result = $conn->query($sql);

?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Test</title>
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

            table {
	border-collapse: collapse;
    font-family: Tahoma, Geneva, sans-serif;
}
table td {
	padding: 15px;
}
table thead td {
	background-color:rgb(6, 64, 136);
	color: #ffffff;
	font-weight: bold;
	font-size: 13px;
	border: 1px solid #54585d;
}

table tbody td {
	color: #636363;
	border: 1px solid #dddfe1;
}
table tbody tr {
	background-color: #f9fafb;
}
table tbody tr:nth-child(odd) {
	background-color: #ffffff;
}

.hoverTable tr:hover {
	background-color:rgb(99, 183, 134);
}

.hoverTable thead td {
	background-color:rgb(6, 64, 136);
	color: #ffffff;
	font-weight: bold;
	font-size: 13px;
	border: 1px solid #54585d;
}


        </style>
	</head>

	
	
	<body>

		<header>

		<?php include "include/nav.php" ?>
            
			<h1>Vocabulary Recall Test</h1>
		</header>

		<main>
        
            <details>
                <summary>Additional info.</summary>
                <p>Select how many words you wish to test yourself on from the drop-down menu below. This will return words that you have tested the least up to the value you have chose. <p>
                <p>The levenshtein value.</p>
            </details>

     <div class="custom-select">
        <form method="post">
            <label for="dropdown">Choose a Category and how many words to test:</label>
                <select name="category" id="category">
                    <option value="">-- Category --</option>    
                        <?php
                            if ($result->num_rows > 0 ) {
                              while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['category_desc']) . '">' . htmlspecialchars($row['category_desc']) . '</option>';
                              }
                            }
                        ?>
                </select>
                <select name="dropdown" id="dropdown">
                    <option>-- Amount of words to test -- </option>
                    <!-- <option value="10" <?php echo (isset($_POST['dropdown']) && $_POST['dropdown'] == '10') ? 'selected' : ''; ?>>10</option> -->
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                <input type="submit" value="Submit">
            </form>
        </div>

            <table id="dataTable" class="hoverTable">
                <thead>
                    <tr>
                        <td>French Text</td>
                        <td>Answer</td>
                        <td>Leveshtein</td>
                        <td>English Text</td>
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

                //php code to select from db
                //If a category has been chosen, it needs to be in the SQL select.
                if (empty($selected_category)) {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' ORDER BY `test_count` ASC limit $selected_value";
                } else {
                    $sql = "SELECT * FROM `tb_vocab`WHERE user_id='$user_id' and category_desc='$selected_category' ORDER BY `test_count` ASC limit $selected_value";
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
                        //Update query
                        $updateQuery = "UPDATE `tb_vocab` SET `test_count`='$test_cnt' WHERE id = '$id'";
                        $runquery = mysqli_query($conn, $updateQuery);
                    } //close while
                } //close if
            }?>
            </tbody>
        </table>


                <!-- Add button to reveal hidden column. -->
                <button id="viewColumnBtn" onclick="toggleColumn(3)">Reveal</button> 
                
                <script>
                    //This script will automatically press the 'Reveal' button when page is loaded to hide column
                    window.onload = function() {
                        setTimeout(function() {
                            document.getElementById("viewColumnBtn").click(); },0);
                    };
                </script>

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