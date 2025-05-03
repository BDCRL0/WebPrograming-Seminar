<?php
// filepath: c:\xampp\htdocs\recipe_project\pages\index.php
session_start();

$page = isset($_GET['page']) ? $_GET['page'] : 'main';

if ($page === 'logout') {
    // Destroy the session and redirect to login
    session_destroy();
    header('Location: login.php');
    exit;
}

$allowed_pages = [
    'main' => 'main.php',
    'images' => 'images.php',
    'contact' => 'contact.php',
    'messages' => 'messages.php',
    'login' => 'login.php',
    'register' => 'register.php',
];

if (array_key_exists($page, $allowed_pages)) {
    require $allowed_pages[$page];
} else {
    echo "Page not found.";
}
?>