<?php
require_once '../includes/config.php'; // Include the database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Insert the message into the database
    $query = "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param('sss', $name, $email, $message);
        if ($stmt->execute()) {
            $success_message = "Your message has been sent successfully!";
        } else {
            $error_message = "Failed to send your message. Please try again.";
        }
        $stmt->close();
    } else {
        $error_message = "Database error: " . $mysqli->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="main.php">WORLD RECIPES</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="main.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="images.php">Images</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=messages">Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=logout">Logout</a></li>
            </ul>
            <?php if (isset($_SESSION['user'])): ?>
                <span class="navbar-text text-light">
                    Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?>
                </span>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Contact Form -->
    <div class="container mt-5">
        <h2 class="text-center">Contact Us</h2>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Your Message</label>
                <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" name="send_message" class="btn btn-primary btn-block">Send Message</button>
        </form>
    </div>
</body>
</html>