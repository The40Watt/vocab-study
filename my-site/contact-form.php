<?php
session_start();
//Open db connection.
include("include/connection.php");
include("include/functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //get user_id to distingush which user is inputting data.
    $user_id = $_SESSION['user_id'];

    //Sanitise and validate input
    $name = $conn-> real_escape_string(trim($_POST["name"]));
    $email = $conn-> real_escape_string(trim($_POST["email"]));
    $subject = $conn-> real_escape_string(trim($_POST["subject"]));
    $message = $conn-> real_escape_string(trim($_POST["message"]));
    $read_msg = 'Y';

    echo ("name:") . $name;
    echo ("email:") . $email;
    echo ("subject:") . $subject;
    echo ("message:") . $message;
    echo ("read_msg:") . $read_msg;
    echo ("id_user:") . $user_id;

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo ("something empty?");
        die("<p style='color:red;'>All fields are required.</p>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo ("email issue?");
        die("<p style='color:red;'>Invalid email format.</p>");
    } 


    $sql = "insert into tb_message (name, email, subject, message, read_msg, id_user) VALUES ('$name', '$email', '$subject', '$message', '$read_msg', '$user_id')";
    echo ("sql:") . $sql;
    $run = mysqli_query($conn, $sql);
    echo ("sql error:") . $sql->error;

    if ($run) {
        echo "<p style='color:green;'>Message sent successfully!</p>";

        //redirect the user
        header("Location: about.php?contact-email-sent");
    } else {
        echo "<p style=color:red;'>Message failed to send.</p>";

        //redirect the user
        header("Location: about.php?contact-email-fail");
    }

    

    //Prepare SQL
   /* $stmt = $conn->prepare("INSERT INTO tb_message (`name`, `email`, `subject`, `message`, `read_msg`, `id_user`) VALUES (?,?,?,?,?,?)");
    echo ("stmt:") . $stmt;
    $stmt->bind_param("sssssi", $name, $email, $subject, $message, $read_msg, $user_id);

    echo ("stmt:") . $stmt;

    //Execute SQL
    if ($stmt-execute()) {
        echo "<p style='color:green;'>Message sent successfully!</p>";

        //redirect the user
        header("Location: about.php?contact-email-sent");
    } else {
        echo "<p style=color:red;'>Message failed to send.</p>";

        //redirect the user
        header("Location: about.php?contact-email-fail");
    }

    //Close statement & connection
    $stmt->close();*/
}



/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    //Validate inputs
    if (empty($name) || empty($email) || empty($message)) {
        die("<p style='color:red;'>All fields are required.</p>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<p style='color:red;'>Invalid email format.</p>");
    }

    //email settings
    $to = "stlennon21@gmail.com";
    $subject = "New Contact Form Submission";
    $headers = "From: $email\r\nReply-To: $email\r\n\Content-Type: text/plain; charset=UTF-8";

    $email_body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    //send email
    if (mail($to, $subject, $email_body, $headers)) {
        echo "<p style='color:green;'>Message sent successfully!</p>";

        //redirect the user
        header("Location: about.php?contact-email-sent");
    } else {
        echo "<p style=color:red;'>Message failed to send.</p>";

        //redirect the user
        header("Location: about.php?contact-email-fail");
    }
}*/

?>