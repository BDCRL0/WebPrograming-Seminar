<?php
require_once '../includes/session.php'; // Centralized session handling

// Redirect to the login page if no session exists
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

// Clear session and redirect to the login page
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header('Location: index.php?page=login'); // Redirect to the login page
exit;