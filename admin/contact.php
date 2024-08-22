<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$config = include('config.php');

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    include('connection.php');


    // Extract data
    $name = isset($data['name']) ? $data['name'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $message = isset($data['message']) ? $data['message'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New contact created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $config['mail']['host'];
        $mail->SMTPAuth = $config['mail']['smtp_auth'];
        $mail->Port = $config['mail']['port'];
        $mail->Username = $config['mail']['username'];
        $mail->Password = $config['mail']['password'];

        // Recipients
        $mail->setFrom('floraisondental@alruya.link', 'Floraisondental');
        $mail->addAddress('hsntkhan1614@gmail.com', 'Floraisondental');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form Submission';
        $mail->Body    = "Name: $name<br>Email: $email<br>Message: $message";

        $mail->send();
        $response['status'] = 'success';
        $response['message'] = 'Message has been sent';

        // Return a response
        echo json_encode(['status' => 'success', 'message' => 'Form submitted successfully']);
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);