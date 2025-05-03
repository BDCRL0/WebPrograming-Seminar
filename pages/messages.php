<?php
require_once '../includes/config.php'; // Include the database connection

// Check if a session is already active before starting a new one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $message_id = intval($_POST['message_id']);
    $table = $_POST['table']; // Determine which table to delete from

    if ($table === 'messages') {
        $query = "DELETE FROM messages WHERE id = ?";
    } elseif ($table === 'chatbot_messages') {
        $query = "DELETE FROM chatbot_messages WHERE id = ?";
    }

    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $message_id);
        $stmt->execute();
        $stmt->close();
        header('Location: messages.php'); // Redirect to avoid form resubmission
        exit;
    }
}

// Pagination setup
$limit = 5; // Number of records per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search_query = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

// Fetch messages from the "messages" table
$query_messages = "SELECT id, name, email, message, created_at FROM messages 
                   WHERE name LIKE ? OR email LIKE ? OR message LIKE ?
                   ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt_messages = $mysqli->prepare($query_messages);
$search_term = "%$search_query%";
$stmt_messages->bind_param('sssii', $search_term, $search_term, $search_term, $limit, $offset);
$stmt_messages->execute();
$result_messages = $stmt_messages->get_result();

// Fetch total count for pagination
$query_messages_count = "SELECT COUNT(*) AS total FROM messages 
                         WHERE name LIKE ? OR email LIKE ? OR message LIKE ?";
$stmt_count = $mysqli->prepare($query_messages_count);
$stmt_count->bind_param('sss', $search_term, $search_term, $search_term);
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_messages = $count_result->fetch_assoc()['total'];

// Fetch messages from the "chatbot_messages" table
$query_chatbot = "SELECT id, username, message, created_at FROM chatbot_messages 
                  WHERE username LIKE ? OR message LIKE ?
                  ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt_chatbot = $mysqli->prepare($query_chatbot);
$stmt_chatbot->bind_param('ssii', $search_term, $search_term, $limit, $offset);
$stmt_chatbot->execute();
$result_chatbot = $stmt_chatbot->get_result();

// Fetch total count for chatbot messages
$query_chatbot_count = "SELECT COUNT(*) AS total FROM chatbot_messages 
                        WHERE username LIKE ? OR message LIKE ?";
$stmt_chatbot_count = $mysqli->prepare($query_chatbot_count);
if ($stmt_chatbot_count) {
    $stmt_chatbot_count->bind_param('ss', $search_term, $search_term);
    $stmt_chatbot_count->execute();
    $count_chatbot_result = $stmt_chatbot_count->get_result(); // Correct variable name
    $total_chatbot_messages = $count_chatbot_result->fetch_assoc()['total']; // Use the correct result variable
} else {
    $total_chatbot_messages = 0; // Default to 0 if the query fails
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .table-container {
            margin-top: 30px;
        }
        .table {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .table th {
            background-color: #343a40;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
        }
        .btn-danger {
            font-size: 0.9rem;
        }
        .table-title {
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            color: #343a40;
        }
        .pagination {
            justify-content: center;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link active" href="messages.php">Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=logout">Logout</a></li>
            </ul>
            <?php if (isset($_SESSION['user'])): ?>
                <span class="navbar-text text-light">
                    Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?>
                </span>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="container mt-4">
        <form action="messages.php" method="GET" class="form-inline justify-content-center">
            <input type="text" name="search" class="form-control mr-2" placeholder="Search messages..." value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Messages from Contact Form -->
    <div class="container table-container">
        <h2 class="table-title">Messages from Contact Form</h2>
        <?php if ($result_messages && $result_messages->num_rows > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Sent At</th>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'Guest'): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_messages->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['message']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'Guest'): ?>
                                <td>
                                    <form action="messages.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        <input type="hidden" name="message_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="table" value="messages">
                                        <button type="submit" name="delete_message" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= ceil($total_messages / $limit); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="messages.php?page=<?= $i ?>&search=<?= urlencode($search_query) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php else: ?>
            <p class="text-center">No messages found.</p>
        <?php endif; ?>
    </div>

    <!-- Messages from the Chatbot -->
    <div class="container table-container">
        <h2 class="table-title">Messages from the Chatbot</h2>
        <?php if ($result_chatbot && $result_chatbot->num_rows > 0): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Message</th>
                        <th>Sent At</th>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'Guest'): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_chatbot->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['message']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['username'] !== 'Guest'): ?>
                                <td>
                                    <form action="messages.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        <input type="hidden" name="message_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="table" value="chatbot_messages">
                                        <button type="submit" name="delete_message" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= ceil($total_chatbot_messages / $limit); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="messages.php?page=<?= $i ?>&search=<?= urlencode($search_query) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php else: ?>
            <p class="text-center">No data found in the chatbot messages table.</p>
        <?php endif; ?>
    </div>
</body>
</html>