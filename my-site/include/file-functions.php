<?php

/*
    Function to check which TL has been passed in.
    Depending on the TL, set the right dictionary language.
    Open the corresponding file and extract a random word. 

    CHANGE HISTORY:

    10-03-25:   Added the new functionality to cater for different languages.
*/


function find_session_word($lang_code) {


    //Declare variables
    $dictionary = '';

    //Set the dicationary depending on the users set target language.
    if ($lang_code == "fr") {
        $dictionary = 'french.txt';
    } elseif ($lang_code == "es") {
        $dictionary = 'spanish.txt';
    } elseif ($lang_code == "es") {
        $dictionary = 'portuguese.txt';
    } elseif ($lang_code == 'de') {
        $dictionary = 'german.txt';
    } elseif ($lang_code == 'it') {
        $dictionary = 'italian.txt';
    } else {
        $dictionary = 'french.txt';
    }

  
    //Open the file. The 'r' parameter means it is open for read-only.
    $myfile = fopen("dictionary/" . $dictionary , "r") or die("Unable to open dictionary file.");
    
    $f_contents = file("dictionary/" . $dictionary);   

    //Get a random line from the file.
    $line = $f_contents[array_rand($f_contents)];

    $data = $line;
    
    //Close the open file. 
    fclose($myfile);

    return $data;

    
/**
 * Code below for stripping unwanted chars
 */


//  $inputfile = "dictionary/dutch.txt";
//  $outputfile = "dictionary/dutch-output.txt";

// $lines = file($inputfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// if ($lines === false) {
//  die("Error 1");
// }

// $modifiedLines = [];
// foreach($lines as $line) {
//  $modifiedLine = preg_replace('/\/.*/', '', $line);
//  $modifiedLines[] = $modifiedLine;
// }

// file_put_contents($outputfile, implode("\n", $modifiedLines));

  
}
