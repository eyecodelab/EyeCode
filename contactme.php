<?php   
    require("./mailing/mailfunction.php");

    // Function to validate and format phone number
    function validatePhone($phone) {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // Check if the number is valid
        if (preg_match('/^\d{10}$/', $phone)) {
            // Format the number as (415) 555-2671
            $phone = preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $phone);
            return $phone;
        } elseif (preg_match('/^\d{11}$/', $phone)) {
            // Format for 11 digit number starting with country code, e.g., 14155552671
            $phone = preg_replace('/^(\d{1})(\d{3})(\d{3})(\d{4})$/', '+$1 ($2) $3-$4', $phone);
            return $phone;
        }

        // If not valid, return false
        return false;
    }

    // Get POST data
    $name = $_POST["name"];
    $phone = $_POST['phone'];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Validate phone number
    $phone = validatePhone($phone);
    if ($phone === false) {
        echo '<center><h1>Invalid phone number! Please use a valid format.</h1></center>';
        exit;
    }

    // Prepare email body
    $body = "<ul><li>Name: ".$name."</li><li>Phone: ".$phone."</li><li>Email: ".$email."</li><li>Message: ".$message."</li></ul>";

    // Send email
    $status = mailfunction("eyecodelab@gmail.com", "EyeCode", $body); // receiver
    if($status)
        echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
    else
        echo '<center><h1>Error sending message! Please try again.</h1></center>';    
?>
