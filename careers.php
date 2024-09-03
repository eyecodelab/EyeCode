<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('./vendor/autoload.php');
require 'mailing/mailingvariables.php';

function mailfunction($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment_path = false, $attachment_name = false){

    $mail = new PHPMailer(true); // Enable exceptions for better error handling
    $mail->isSMTP();

    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $mail->Host = $GLOBALS['mail_host'];
    $mail->Port = $GLOBALS['mail_port'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = true;
    $mail->Username = $GLOBALS['mail_sender_email'];
    $mail->Password = $GLOBALS['mail_sender_password'];
    $mail->setFrom($GLOBALS['mail_sender_email'], $GLOBALS['mail_sender_name']);
    $mail->addAddress($mail_reciever_email, $mail_reciever_name);
    $mail->Subject = 'Someone Contacted You!';
    $mail->isHTML(true);
    $mail->msgHTML($mail_msg);

    // Attach the file directly from the temporary upload location
    if ($attachment_path !== false && $attachment_name !== false) {
        if (file_exists($attachment_path)) {
            $mail->addAttachment($attachment_path, $attachment_name); // Attach the file using its original name
        } else {
            throw new Exception('Attachment file does not exist: ' . $attachment_path);
        }
    }
    
    $mail->AltBody = 'This is a plain-text message body';

    try {
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        // Log the error message or handle it as necessary
        return false;
    }
}

// Handling the file upload and sending the email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST["name"], ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
    $applyfor = htmlspecialchars($_POST["status"], ENT_QUOTES, 'UTF-8');
    $experience = htmlspecialchars($_POST["experience"], ENT_QUOTES, 'UTF-8');
    $otherdetails = htmlspecialchars($_POST["details"], ENT_QUOTES, 'UTF-8');

    $body = "<ul>
                <li>Name: ".$name."</li>
                <li>Phone: ".$phone."</li>
                <li>Email: ".$email."</li>
                <li>Apply For: ".$applyfor."</li>
                <li>Experience: ".$experience." Yrs.</li>
                <li>Resume (Attached Below):</li>
             </ul>";

    // Check if a file was uploaded
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK) {
        $tempfile = $_FILES["fileToUpload"]["tmp_name"];
        $filename = $_FILES["fileToUpload"]["name"];

        // Send the email with the uploaded file as an attachment
        $status = mailfunction("eyecodelab@gmail.com", "Company", $body, $tempfile, $filename); // Receiver's email

        if ($status) {
            echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
        } else {
            echo '<center><h1>Error sending message! Please try again.</h1></center>';
        }
    } else {
        echo '<center><h1>Error uploading file! Please try again.</h1></center>';
    }
}
?>
