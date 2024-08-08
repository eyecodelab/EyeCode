<?php
require("./mailing/mailfunction.php");

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST["name"];
    $phone = $_POST['phone'];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Sanitize input
    $name = htmlspecialchars($name);
    $phone = htmlspecialchars($phone);
    $email = htmlspecialchars($email);
    $message = htmlspecialchars($message);

    $body = "<ul>
                <li>Name: $name</li>
                <li>Phone: $phone</li>
                <li>Email: $email</li>
                <li>Message: $message</li>
            </ul>";

    // Call mail function with appropriate parameters
    $status = mailfunction("minhpro103@gmail.com", "Company", $body); // Replace with actual recipient email

    if ($status) {
        echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
    } else {
        echo '<center><h1>Error sending message! Please try again.</h1></center>';
    }
} else {
    echo '<center><h1>Invalid request method!</h1></center>';
}
?>
