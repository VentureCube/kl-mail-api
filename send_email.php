<?php
// Load PHPMailer (Install using Composer: composer require phpmailer/phpmailer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'vendor/autoload.php';

// Set response headers
header("Content-Type: application/json");

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the JSON payload
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate inputs
        if (empty($data['company_name']) || empty($data['your_name']) || empty($data['your_email']) || empty($data['phone_number']) || empty($data['cargo_info'])) {
            throw new Exception("All fields are required.");
        }

        // Validate email format
        if (!filter_var($data['your_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Email details
        $to = "cs@kingdomlogistics.me"; // Replace with your email address
        $subject = "New Contact Form Submission - Kingdom Logistics";
        $message = "
            <html>
            <head>
            <title>New Contact Form Submission</title>
            </head>
            <body>
            <h2>Contact Form Details</h2>
            <p><strong>Company Name:</strong> {$data['company_name']}</p>
            <p><strong>Your Name:</strong> {$data['your_name']}</p>
            <p><strong>Your Email:</strong> {$data['your_email']}</p>
            <p><strong>Phone Number:</strong> {$data['phone_number']}</p>
            <p><strong>Cargo Information:</strong> {$data['cargo_info']}</p>
            </body>
            </html>
        ";

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'your-email-password'; // Replace with your Gmail password or App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email setup
        $mail->setFrom('no-reply@kingdomlogistics.me', 'Kingdom Logistics'); // Static "From" address
        $mail->addAddress($to); // Recipient email
        $mail->addReplyTo($data['your_email'], $data['your_name']); // Reply-To
        $mail->isHTML(true); // Send as HTML
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        if ($mail->send()) {
            echo json_encode(["success" => true, "message" => "Email sent successfully."]);
        } else {
            throw new Exception("Email could not be sent. Mailer Error: " . $mail->ErrorInfo);
        }
    } catch (Exception $e) {
        // Return error response
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
