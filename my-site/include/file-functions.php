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
    } else {
        $dictionary = 'default.txt';
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

    


  
}
