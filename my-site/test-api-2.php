<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

    include("include/connection.php");
	include("include/functions.php");
    include("include/error-logging.php");
    include_once("include/constants.php");

    //Find random words from users 'tb_vocab' table
    $words = find_cloud_words();

    if (empty($words)) {
        die("No words in database");
    }

    print_r($words);


    if (!isset($_GET['word'])) {
        $randomRow = $words[array_rand($words)];
        //$currentWord = $randomRow['fr_text'];
        $currentWord = is_array($randomRow) ? $randomRow['fr_text'] : $randomRow;
        $currentWord = (string) $currentWord; //forcing to be a string
        
        header("Location: ?word=" . urlencode($currentWord));
        exit();
    } else {
        $currentWord = $_GET['word'];
    }

    print_r($currentWord);

// --------------- CONFIGURATION --------------- //
// List of French words you want to quiz on
//$words = ['heureux', 'triste', 'rapide', 'lent', 'fort', 'faible'];


// Fetch synonyms from ConceptNet
function getSynonyms($word, $lang = 'fr') {
$url = "https://api.conceptnet.io/query?node=/c/$lang/$word&rel=/r/Synonym&limit=10";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
$synonyms = [];

if (isset($data['edges'])) {
    foreach ($data['edges'] as $edge) {
        $end = $edge['end']['label'] ?? '';
        if (is_string($end) && is_string($word) && strtolower($end) != strtolower($word)) {
            $synonyms[] = $end;
        }
    }
}

return $synonyms;
}

// Get synonyms for the current word
$synonyms = getSynonyms($currentWord);

// --------------- GENERATE QUIZ OPTIONS --------------- //
$options = [];
$correctAnswer = '';

if (!empty($synonyms)) {
// Pick one correct synonym randomly
$correctAnswer = $synonyms[array_rand($synonyms)];
$options[] = $correctAnswer;

// Add random wrong options from your main words list (that are NOT synonyms)
while (count($options) < 4) {
    $randomRow = $words[array_rand($words)];
    $wrongWord = is_array($randomRow) ? $randomRow['fr_text'] : $randomRow;

    if (is_string($wrongWord) && !in_array($wrongWord, $options) && strtolower($randomWrong) != strtolower($currentWord)) {
    $options[] = $wrongWord;
    }
}

// Shuffle options
shuffle($options);
} else {
echo "No synonyms found for '$currentWord'. Try refreshing!";
exit();
}

// Check answer if form submitted
$resultMessage = '';

if (isset($_POST['answer'])) {
$userAnswer = $_POST['answer'];
if ($userAnswer == $correctAnswer) {
$resultMessage = "<span style='color: green;'>Correct! '$correctAnswer' is a synonym of '$currentWord'.</span>";
} else {
$resultMessage = "<span style='color: red;'>Incorrect! The correct synonym was '$correctAnswer'.</span>";
}
}

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




<h1>Find the synonym for: <u><?= htmlspecialchars($currentWord) ?></u></h1>

<?php if ($resultMessage): ?>
<p><?= $resultMessage ?></p>
<a href="?" style="font-weight: bold;">Next Word</a>
<?php else: ?>
<form method="post">
<?php foreach ($options as $option): ?>
<div>
<input type="radio" id="<?= htmlspecialchars($option) ?>" name="answer" value="<?= htmlspecialchars($option) ?>" required>
<label for="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></label>
</div>
<?php endforeach; ?>
<button type="submit">Submit Answer</button>
</form>
<?php endif; ?>



</main>


		<!-- Add the footer. -->
		<?php include "include/footer.php" ?>
</body>
</html>