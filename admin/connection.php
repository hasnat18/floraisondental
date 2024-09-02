<?php

$servername = "shareddb-c.hosting.stackcp.net";
$username = "floraisondental-3633447a";
$password = "zkS3z5<3£XWJ";
$dbname = "floraisondental-3633447a";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}