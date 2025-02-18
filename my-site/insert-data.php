no<?php

   // echo "<pre>";
   // print_r($_POST);
   // echo "</pre>";

//Connection variables. 

$server = "localhost";
$dbuser = "root";
$password = "";
$database_name = "french";

$conn = mysqli_connect($server, $dbuser, $password, $database_name);

$french = $_POST['fr_text'];
$english = $_POST['en_text'];

$sql = "INSERT INTO `tb_vocab`(`fr_text`, `en_text`) VALUES ('$french','$english')";

$run = mysqli_query($conn, $sql);

if($run) {
    echo "The data was inserted successfully.";
} else {
    echo "Error: The data was not inserted.";
}

//Code to confirm to user if db connection works. 
/*
if($conn) {
    echo "Database Connection Established Successfully";
} else {
    echo "Database Not Connected";
}
*/
//CREATE

?>

