<?php
require_once '../includes/session.php'; // Handles session_start()

// Handle logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php?page=login"); // Redirect to login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main - World Recipes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fdfbfb, #ebedee);
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #222;
        }
        .navbar .nav-link, .navbar .navbar-brand {
            color: #fff !important;
        }
        .navbar .nav-link:hover {
            color: gold !important;
        }
        .navbar .user-info {
            color: gold;
            font-size: 0.9rem;
            margin-left: 10px;
        }
        .carousel-item video {
            width: 100%;
            height: auto;
        }
        .recipe-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            padding: 20px;
        }
        .recipe-card img {
            max-width: 100%;
            border-radius: 10px;
        }
        #chatbot {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        #chatbot-header {
            background: #222;
            color: #fff;
            padding: 10px;
            font-weight: bold;
        }
        #chatbot-body {
            display: none;
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
        }
        #chatbot-form {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        #chatbot-input {
            flex: 1;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        #chatbot-form button {
            background: #222;
            color: #fff;
            border: none;
            margin-left: 5px;
            padding: 5px 10px;
            border-radius: 5px;
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
            <li class="nav-item"><a class="nav-link active" href="main.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=images">Images</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=contact">Contact</a></li>
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
<!-- Carousel -->
<div id="videoCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#videoCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#videoCarousel" data-slide-to="1"></li>
        <li data-target="#videoCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <video class="d-block w-100" controls muted loop>
                <source src="../assets/videos/video1.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="carousel-item">
            <video class="d-block w-100" controls muted loop>
                <source src="../assets/videos/video2.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="carousel-item">
            <video class="d-block w-100" controls muted loop>
                <source src="../assets/videos/video3.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
    <a class="carousel-control-prev" href="#videoCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#videoCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- Recipes -->
<div class="container mt-5">
    <div class="row">
        <?php
        $recipes = [
            ['title' => 'Italian Pasta', 'img' => '../assets/images/italy.jpg', 'description' => 'Classic Italian pasta with tomato and basil.'],
            ['title' => 'Japanese Sushi', 'img' => '../assets/images/japan.jpg', 'description' => 'Traditional sushi rolls from Japan.'],
            ['title' => 'Mexican Tacos', 'img' => '../assets/images/mexico.jpg', 'description' => 'Spicy beef tacos from Mexico.']
        ];
        foreach ($recipes as $recipe):
        ?>
        <div class="col-md-4">
            <div class="recipe-card">
                <h4><?= $recipe['title'] ?></h4>
                <img src="<?= $recipe['img'] ?>" alt="<?= $recipe['title'] ?>">
                <p><?= $recipe['description'] ?></p>
                <button onclick="getLocation(this)">Show My Location for <?= $recipe['title'] ?></button>
                <p class="geo-info">Location: Awaiting user permission...</p>
                <div class="map-frame" style="margin-top: 10px;"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Chatbot -->
<div id="chatbot">
    <div id="chatbot-header">
        Chat with us
        <button id="chatbot-toggle" class="btn btn-sm btn-light float-right">_</button>
    </div>
    <div id="chatbot-body">
        <div id="chatbot-messages"></div>
        <form id="chatbot-form">
            <input type="text" id="chatbot-input" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script>
    // Chatbot toggle
    document.getElementById('chatbot-toggle').onclick = () => {
        const body = document.getElementById('chatbot-body');
        body.style.display = body.style.display === 'block' ? 'none' : 'block';
    };

    document.getElementById('chatbot-form').onsubmit = function(e) {
        e.preventDefault();
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        if (message) {
            const messages = document.getElementById('chatbot-messages');
            messages.innerHTML += `<div>You: ${message}</div>`;
            messages.innerHTML += `<div style="color: gray;">Bot: Your message has been saved!</div>`;
            input.value = '';
            messages.scrollTop = messages.scrollHeight;
        }
    };

    // Geolocation for each recipe
    function getLocation(button) {
        const parent = button.parentElement;
        const info = parent.querySelector('.geo-info');
        const mapFrame = parent.querySelector('.map-frame');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    info.innerHTML = `Latitude: ${lat.toFixed(3)}, Longitude: ${lon.toFixed(3)}`;
                    mapFrame.innerHTML = `<iframe src="https://maps.google.com/maps?q=${lat},${lon}&hl=es;z=14&amp;output=embed" width="100%" height="200" frameborder="0" style="border:0;" allowfullscreen></iframe>`;
                },
                error => {
                    info.innerHTML = "Geolocation failed.";
                }
            );
        } else {
            info.innerHTML = "Geolocation not supported.";
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
