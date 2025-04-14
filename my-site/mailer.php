<!-- 
    
    AUTHOR: STEPHEN LENNON
    DATE: 27-03-2025

    HIGHLEVEL DESCRIPTION: 
    This file is used to assign the properties to user PHPMailer.

    DETAILS:
    This file is called from 'send-password-reset.php'. It will control the sending of an email.
    Currently the application is in localhost, so using a third-party email function to send the emails (outlook, Gmail use 2FA which blocks PHPMailer)
    I will need to reconfigure this file when application is moved to a server. 

    CHANGE HISTORY:


-->

<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    include("mailer/PHPMailer/src/Exception.php");
    include("mailer/PHPMailer/src/PHPMailer.php");
    include("mailer/PHPMailer/src/SMTP.php");

    $mail = new PHPMailer(true);

    /**
     * use a DOT end file for configuration????
     */
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = 'html';
    

    //Mailgun - not free
    // $mail->Host = 'smtp.mailgun.org';
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port = 587;
    // $mail->Username = "";
    // $mail->Password = "\";

    //Titan email, not authenticating
    // $mail->Host = 'smtp.titan.email';
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port = 587;
    // $mail->Username = "";
    // $mail->Password = "";

    //iol.ie
    $mail->Host = 'webmail.email.hosting';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->Username = "";
    $mail->Password = "";

    //Allow HTML in emails
    $mail->isHTML(true);

    return $mail;


