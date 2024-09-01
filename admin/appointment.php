<?php

// Log the request method for debugging
file_put_contents('php://stderr', print_r($_SERVER['REQUEST_METHOD'], TRUE));

// Set headers for CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Use PHPMailer and Exception classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader and configuration
require 'vendor/autoload.php';
$config = include('config.php');

// Initialize response array
$response = array();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: https://v2.floraisondental.com');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 86400'); // Cache for 1 day
    exit(0);
}

// Proceed only if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Decode JSON data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    include('connection.php');

    // Extract data safely
    $name = isset($data['name']) ? $data['name'] : '';
    $phone = isset($data['phone']) ? $data['phone'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $time = isset($data['time']) ? $data['time'] : '';
    $service = isset($data['service']) ? $data['service'] : '';
    $message = isset($data['message']) ? $data['message'] : '';

    // Prepare and execute database insertion
    $stmt = $conn->prepare("INSERT INTO appointments (name, phone, email, time, service, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $phone, $email, $time, $service, $message);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'New appointment created successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();

    // Send an email with PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host = $config['mail']['host']; // Set the SMTP server
        $mail->SMTPAuth = $config['mail']['smtp_auth']; // Enable SMTP authentication
        $mail->Port = $config['mail']['port']; // Set the SMTP port
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable SSL encryption
        $mail->Username = $config['mail']['username']; // SMTP username
        $mail->Password = $config['mail']['password']; // SMTP password

        // Recipients
        $mail->setFrom('floraisondentalinfo@gmail.com', 'Floraisondental');
        $mail->addAddress('floraisondentalinfo@gmail.com', 'Floraisondental'); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Appoinment Form Submission';
        $mail->Body = "Name: $name<br>Phone: $phone<br>Email: $email<br>Time: $time<br>Service: $service<br>Message: $message";

        // Send the email
        $mail->send();
        $response['status'] = 'success';
        $response['message'] = 'Form submitted and email sent successfully';
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    // Handle invalid request methods
    header('Content-Type: application/json');
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

// Output the JSON response
echo json_encode($response);