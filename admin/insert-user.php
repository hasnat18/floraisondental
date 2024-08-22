<?php
include('connection.php');

// Insert a sample admin user
$password_hash = password_hash('admin123', PASSWORD_DEFAULT); // hash the password
$sql = "INSERT INTO users (username, password) VALUES ('admin', '$password_hash')";

if ($conn->query($sql) === TRUE) {
    echo "New user created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();