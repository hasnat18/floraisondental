<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$config = include('config.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);


    include('connection.php');


    // Extract data
    $name = isset($data['name']) ? $data['name'] : '';
    $phone = isset($data['phone']) ? $data['phone'] : '';
    $email = isset($data['email']) ? $data['email'] : '';
    $time = isset($data['time']) ? $data['time'] : '';
    $service = isset($data['service']) ? $data['service'] : '';
    $message = isset($data['message']) ? $data['message'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO appointments (name, phone, email, time, service, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $phone, $email, $time, $service, $message);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New appointment created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    print_r($config['mail']['host']);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = $config['mail']['host'];
        $mail->SMTPAuth = $config['mail']['smtp_auth'];
        $mail->Port = $config['mail']['port'];
        $mail->SMTPSecure = 'ssl';
        $mail->Username = $config['mail']['username'];
        $mail->Password = $config['mail']['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        // Recipients
        $mail->setFrom('floraisondental@alruya.link', 'Floraisondental');
        $mail->addAddress('hsntkhan1614@gmail.com', 'Floraisondental');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form Submission';
        $mail->Body    = "name: $name<br>phone: $phone<br>email: $email<br>time: $time<br>service: $service<br>message: $message";

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