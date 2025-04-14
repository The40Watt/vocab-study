<?php

	//Put user_id into session and check on each page to see if the user_id is legit.
	session_start();

	$_SESSION;
	include("include/connection.php");
	include("include/functions.php");
    include("include/file-functions.php");

  //this will ensure PHP displays all errors
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
  //Declare variables
  $cnt = 0;
  $word = '';
  $test_cnt = 0;
  $rand_word = '';

	$user_data = check_login($conn); //if logged in, this variable will contain the user data
	$rowcount = count_records($cnt);
  $next_word = next_word($word);
	$tested_count = number_of_tests($test_cnt);

  //If the random word for the session has already been set, then skip this call.
  if (!isset($_SESSION['session_word'])) {
    $_SESSION['session_word'] = find_session_word();
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Page</title>
    <link rel="stylesheet" href="css/col-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    <header>
			<?php include "include/nav.php" ?>
    </header>
    


    <div class="main-section">
        <a href=""><i class="fa-solid fa-circle-dot"></i> Contact Page</a>
        <div class="page-title">
            <h1>Get in touch with us for <br> more information</h1>
        </div>
        <p>If you need help or have a question, we're here for you.</p>
    </div>

    <div class="cards-wrapper">
        <div class="card">
            <div class="img-container a">
                <img src="images/icon-1.png" width="100px" alt="">
            </div>
            <h1>San Francisco</h1>
            <p>sanfarisco@finegap.io</p>
            <p>(415) 931-1616</p>
            <a href="">View Location</a>
        </div>

        <div class="card">
            <div class="img-container b">
                <img src="images/icon-2.png" width="100px" alt="">
            </div>
            <h1>Paris</h1>
            <p>paris@finegap.io</p>
            <p>(415) 931-1616</p>
            <a href="">View Location</a>
        </div>

        <div class="card">
            <div class="img-container c">
                <img src="images/icon-3.png" width="100px" alt="">
            </div>
            <h1>Egypt</h1>
            <p>egypt@finegap.io</p>
            <p>(415) 931-1616</p>
            <a href="">View Location</a>
        </div>
    </div>

    <div class="cards-wrapper">
        <div style="width:70%;" class="card">
            <div class="img-container a">
                <img src="images/icon-1.png" width="100px" alt="">
            </div>
            <h1>Quick Links</h1>
            <p>sanfarisco@finegap.io</p>
            <p>(415) 931-1616</p>
            <a href="">View Location</a>
        </div>
    </div>


    <div class="cards-wrapper">
        <div style="width:70%;" class="card">
            <div class="img-container a">
                <img src="images/icon-1.png" width="100px" alt="">
            </div>
            <h1>Random Word</h1>
            <p>sanfarisco@finegap.io</p>
            <p>(415) 931-1616</p>
            <a href="">View Location</a>
        </div>
        <div style="width:70%;" class="card">
            <div class="img-container a">
                <img src="images/icon-1.png" width="100px" alt="">
            </div>
            <h1>Learning Tips</h1>
            <p>sanfarisco@finegap.io</p>
            <p>(415) 931-1616</p>
            <a href="">View Location</a>
        </div>
    </div>

</body>
</html>