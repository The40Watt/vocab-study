<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Up: Test API</title>
    <link rel="stylesheet" href="css/stylin.css">
</head>
<body>
<header>
  <?php include "include/nav.php" ?>
</header>

<main>

<?php
$word = "happy"; // Example word

$url = "https://api.datamuse.com/words?rel_syn=" . urlencode($word); // Get synonyms

$response = file_get_contents($url);

if ($response !== false) {
    $synonyms = json_decode($response, true);
    foreach ($synonyms as $synonym) {
        echo $synonym['word'] . "<br>";
    }
} else {
    echo "Couldn't fetch synonyms.";
}
?>

</main>


		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
</body>
</html>