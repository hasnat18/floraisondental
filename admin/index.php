<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}