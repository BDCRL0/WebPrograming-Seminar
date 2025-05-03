<?php
// Database connection constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', ''); // Leave empty if no password is set for root
define('DB_NAME', 'demo2');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

// Configuration array (optional, for site settings)
$config = [
    'menu' => [
        'main' => ['name' => 'Main', 'file' => 'pages/main.php'],
        'images' => ['name' => 'Images', 'file' => 'pages/images.php'],
        'contact' => ['name' => 'Contact', 'file' => 'pages/contact.php'],
        'messages' => ['name' => 'Messages', 'file' => 'pages/messages.php'],
        'login' => ['name' => 'Login', 'file' => 'pages/login.php'],
        'logout' => ['name' => 'Logout', 'file' => 'pages/logout.php'],
    ],
    'site_name' => 'Mik Restaurant',
    'address' => '123 Paradise Street, Sitia',
];
?>