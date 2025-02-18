<?php

//Open db connection.
include("include/connection.php");


$record_id = $_GET['id'];

//echo $record_id;

$sql = "DELETE FROM `tb_vocab` WHERE id='$record_id'";
$run = mysqli_query($conn, $sql);

if($run){
    header("Location: show-data.php?record-delete");
}