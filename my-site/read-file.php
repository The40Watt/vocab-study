<?php


	//this will ensure PHP displays all errors
	error_reporting(E_ALL);
	ini_set('display_errors', 1);


/*$filename = "testfile.txt"




if(file_exists($filename))  {

    $words = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!empty($words)) {

        $randomWord = $words[array_rand($words)];

        echo ($randomWord);
    }else {
        echo ("file empty");
    }



} else {
    echo ("file not found");
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php


    $myfile = fopen("testfile.txt", "r") or die("Unable to open file!");
echo fread($myfile,filesize("testfile.txt"));

    $f_contents = file("testfile.txt");
    
    print_r($f_contents);
    $line = $f_contents[array_rand($f_contents)];
    echo ("------");
    print_r($line);
    $data = $line;

    echo $data;

fclose($myfile);

    ?>
</body>
</html>