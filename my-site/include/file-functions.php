<?php

/**************************************************************
    Function to select date from tb_message_history for a user. 
    Will only ever be 1 row on this table per user. 
*/


function find_session_word() {

    //Open the file. The 'r' parameter means it is open for read-only.
    $myfile = fopen("dictionary/french.txt", "r") or die("Unable to open dictionary file.");
    
    $f_contents = file("dictionary/french.txt");
        


    //Get a random line from the file.
    $line = $f_contents[array_rand($f_contents)];

    $data = $line;
    
    //Close the open file. 
    fclose($myfile);

    return $data;

}