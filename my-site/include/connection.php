<?php


//Connection variables. 

$server = "localhost";
$dbuser = "root";
$password = "";
$database_name = "french";

if(!$conn = mysqli_connect($server, $dbuser, $password, $database_name))
{
    die("Failed to connect."); //If database connection failed, display to user. If you don't see an error, connection worked. 
}