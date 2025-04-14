<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 19-02-2025

    HIGHLEVEL DESCRIPTION: 
    File holds the logic for updating tb_vocab with new words. 

    DETAILS:
    This file is called from the 'edit-record.php' file.
    A check will be done to see which buton on the 'edit-record.php' page the user has pressed - MAKE CHANGE or CANCEL.

    CHANGE HISTORY:

    03-03-25    Added to update tb_vocab SQL to update the 'is_mastered' and 'date_mastered' columns on the table. This is because of a new toggle button
                on the 'edit-record.php' file. 

                Changed the update SQL method to use parameters that are bound. This allowed me to get around an issue with updating a row where the
                string had an apostrophe, i.e. "l'eglise'. 


-->
<?php

    //open db connection
    include("include/connection.php");
    include("include/error-logging.php");

    //Connection to db is open, code in connections.php
if(isset($_POST['SubmitButton']))
{

        $row_id = $_POST['row_id'];
        $fr_text = $_POST['fr_text'];
        $en_text = $_POST['en_text'];
        $is_toggled = $_POST['toggle'] ? 'Y' : 'N';
        $currentDateTime = date("Y-m-d H:i:s");



        //prepare the SQL statement
        $sql = "UPDATE `tb_vocab` SET `fr_text`=?,`en_text`=?,`is_mastered`=?,`date_mastered`=? WHERE id=?";
        $run = $conn->prepare($sql);

        //Bind parameters
        $run->bind_param("ssssi", $fr_text, $en_text, $is_toggled, $currentDateTime, $row_id);
        $run->execute();


        if($run) {
            header("Location: show-data.php?record-updated");
        }
} else {
    header("Location: show-data.php?record-update-cancel");
}
