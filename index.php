<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    // Extracting input fields from the contact form
    $companyName = $input["company_name"] ?? '';
    $yourName = $input["your_name"] ?? '';
    $yourEmail = $input["your_email"] ?? '';
    $phoneNumber = $input["phone_number"] ?? '';
    $cargoInfo = $input["cargo_info"] ?? '';
    $toAddress = "cs@kingdomlogistics.me"; // Replace with KL admin email
    $ventureName = "Kingdom Logistics";
    $logo = "https://www.kingdomlogistics.me/Assets/images/logo.png"; // Replace with KL logo URL
    $bgColor = "#0068d2"; // KL branding color

    $mail = new PHPMailer(true);
    $thankYouMail = new PHPMailer(true);

    try {
        // Server settings for SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'harish@venturecube.ai'; // Replace with your SMTP username
        $mail->Password = 'abzb ixyv gdsr qikf'; // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $thankYouMail->isSMTP();
        $thankYouMail->Host = 'smtp.gmail.com';
        $thankYouMail->SMTPAuth = true;
        $thankYouMail->Username = 'harish@venturecube.ai'; // Replace with your SMTP username
        $thankYouMail->Password = 'abzb ixyv gdsr qikf'; // Replace with your SMTP password
        $thankYouMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $thankYouMail->Port = 587;

        // Email for Admin (New Lead)
        $mail->setFrom($yourEmail, $yourName);
        $mail->addAddress($toAddress, "Kingdom Logistics Admin");
        $mail->addReplyTo($yourEmail, $yourName);

        $adminBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9; }
                .header { background-color: $bgColor; padding: 10px; border-radius: 5px 5px 0 0; text-align: center; color: #fff; }
                .logo { text-align: center; margin: 20px 0; }
                .logo img { max-width: 150px; height: auto; }
                .content { padding: 20px; }
                .footer { margin-top: 20px; text-align: center; font-size: 0.9em; color: #666; }
                .details { margin-bottom: 10px; }
                .details span { font-weight: bold; }
            </style>
        </head>
        <body>
        <div class='container'>
            <div class='logo'><img src='$logo' alt='Logo'></div>
            <div class='header'><h1>New Contact Lead</h1></div>
            <div class='content'>
                <p>You have received a new lead from the contact form. Below are the details:</p>
                <div class='details'>
                    <p><span>Company Name:</span> $companyName</p>
                    <p><span>Name:</span> $yourName</p>
                    <p><span>Email:</span> $yourEmail</p>
                    <p><span>Phone Number:</span> $phoneNumber</p>
                    <p><span>Cargo Info:</span> $cargoInfo</p>
                </div>
            </div>
            <div class='footer'>This is an automated message. Please do not reply.</div>
        </div>
        </body>
        </html>";

        $mail->isHTML(true);
        $mail->Subject = "New Contact Lead - Kingdom Logistics";
        $mail->Body = $adminBody;

        // Send Admin Email
        $mail->send();

        // Email for User (Thank You)
        $thankYouMail->setFrom($toAddress, $ventureName);
        $thankYouMail->addAddress($yourEmail, $yourName);

        $userBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9; }
                .header { background-color: $bgColor; padding: 10px; border-radius: 5px 5px 0 0; text-align: center; color: #fff; }
                .logo { text-align: center; margin: 20px 0; }
                .logo img { max-width: 150px; height: auto; }
                .content { padding: 20px; }
                .footer { margin-top: 20px; text-align: center; font-size: 0.9em; color: #666; }
            </style>
        </head>
        <body>
        <div class='container'>
            <div class='logo'><img src='$logo' alt='Logo'></div>
            <div class='header'><h1>Thank You for Contacting Us</h1></div>
            <div class='content'>
                <p>Dear $yourName,</p>
                <p>Thank you for reaching out to $ventureName. We have received your details and will get back to you shortly.</p>
            </div>
            <div class='footer'>Best regards, $ventureName Team</div>
        </div>
        </body>
        </html>";

        $thankYouMail->isHTML(true);
        $thankYouMail->Subject = "Thank You for Contacting Kingdom Logistics";
        $thankYouMail->Body = $userBody;

        // Send Thank You Email
        $thankYouMail->send();

        echo json_encode(["success" => true, "message" => "Emails sent successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // Handle preflight requests
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    exit(0);
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
?>
