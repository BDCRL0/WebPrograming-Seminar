<?php
require_once '../includes/config.php'; // Include the database connection
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['message']) && !empty($data['message'])) {
        $message = htmlspecialchars($data['message']);
        $username = isset($_SESSION['user']) ? $_SESSION['user']['username'] : 'Guest';

        $query = "INSERT INTO chatbot_messages (username, message) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param('ss', $username, $message);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
                exit;
            }
        }
    }
}

echo json_encode(['success' => false]);
exit;
?>