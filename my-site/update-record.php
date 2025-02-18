<?php

    //open db connection
    include("include/connection.php");

    $row_id = $_POST['row_id'];
    $fr_text = $_POST['fr_text'];
    $en_text = $_POST['en_text'];

    $sql = "UPDATE `tb_vocab` SET `fr_text`='$fr_text',`en_text`='$en_text' WHERE id='$row_id'";

    $run = mysqli_query($conn, $sql);

    if($run) {
        header("Location: show-data.php?record-updated");
    }