<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/config.php'; // Include database connection

// Example data for recipes (replace this with database queries if needed)
$recipes = [
    ['image' => 'images/dish1.jpg', 'name' => 'Sushi', 'country' => 'Japan', 'description' => 'A traditional Japanese dish made with vinegared rice and seafood.'],
    ['image' => 'images/dish2.jpg', 'name' => 'Tacos', 'country' => 'Mexico', 'description' => 'A Mexican dish consisting of a folded tortilla filled with various ingredients.'],
    ['image' => 'images/dish3.jpg', 'name' => 'Pizza', 'country' => 'Italy', 'description' => 'A popular Italian dish made with a flatbread base, tomato sauce, and cheese.'],
    ['image' => 'images/dish4.jpg', 'name' => 'Croissant', 'country' => 'France', 'description' => 'A buttery, flaky, and crescent-shaped pastry from France.'],
    ['image' => 'images/dish5.jpg', 'name' => 'Paella', 'country' => 'Spain', 'description' => 'A Spanish rice dish cooked with saffron, seafood, and vegetables.'],
];

// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = strtolower(trim($_GET['search']));
    $recipes = array_filter($recipes, function ($recipe) use ($search_query) {
        return strpos(strtolower($recipe['name']), $search_query) !== false ||
               strpos(strtolower($recipe['country']), $search_query) !== false;
    });
}

// Handle adding to favorites
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_favorites'])) {
    if (!isset($_SESSION['user'])) {
        echo '<div class="alert alert-danger text-center">You must be logged in to add favorites.</div>';
    } else {
        $user_id = $_SESSION['user']['id'];
        $recipe_name = htmlspecialchars($_POST['recipe_name']);

        // Check if the recipe is already in favorites
        $query_check = "SELECT * FROM favorites WHERE user_id = ? AND recipe_name = ?";
        $stmt_check = $mysqli->prepare($query_check);
        $stmt_check->bind_param('is', $user_id, $recipe_name);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo '<div class="alert alert-warning text-center">This recipe is already in your favorites.</div>';
        } else {
            // Add to favorites
            $query_add = "INSERT INTO favorites (user_id, recipe_name) VALUES (?, ?)";
            $stmt_add = $mysqli->prepare($query_add);
            $stmt_add->bind_param('is', $user_id, $recipe_name);
            if ($stmt_add->execute()) {
                echo '<div class="alert alert-success text-center">Recipe added to favorites!</div>';
            } else {
                echo '<div class="alert alert-danger text-center">Failed to add to favorites. Please try again.</div>';
            }
        }
    }
}

// Fetch user's favorite recipes
$favorites = [];
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $query_favorites = "SELECT recipe_name FROM favorites WHERE user_id = ?";
    $stmt_favorites = $mysqli->prepare($query_favorites);
    $stmt_favorites->bind_param('i', $user_id);
    $stmt_favorites->execute();
    $result_favorites = $stmt_favorites->get_result();
    while ($row = $result_favorites->fetch_assoc()) {
        $favorites[] = $row['recipe_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery - Mik Restaurant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            padding: 20px;
        }
        .gallery-item {
            text-align: center;
            width: 300px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .gallery img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .gallery h5 {
            font-size: 1.2rem;
            margin: 10px 0 5px;
        }
        .gallery p {
            font-size: 0.9rem;
            color: #555;
        }
        .search-bar {
            margin: 20px 0;
            text-align: center;
        }
        .search-bar input {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        .iframe-container {
            margin-top: 40px;
            text-align: center;
        }
        iframe {
            width: 100%;
            height: 400px;
            border: none;
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
                <li class="nav-item"><a class="nav-link active" href="images.php">Images</a></li>
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

    <!-- Image Gallery Section -->
    <div class="container">
        <h2 class="text-center my-4">Image Gallery</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <form action="images.php" method="GET">
                <input type="text" name="search" placeholder="Search for a dish or country..." value="<?= htmlspecialchars($search_query) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <!-- Gallery -->
        <div class="gallery">
            <?php if (empty($recipes)): ?>
                <p class="text-center">No results found for "<?= htmlspecialchars($search_query) ?>".</p>
            <?php else: ?>
                <?php foreach ($recipes as $recipe): ?>
                    <div class="gallery-item">
                        <img src="<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['name']) ?>">
                        <h5><?= htmlspecialchars($recipe['name']) ?></h5>
                        <p><strong>Country:</strong> <?= htmlspecialchars($recipe['country']) ?></p>
                        <p><?= htmlspecialchars($recipe['description']) ?></p>
                        <form action="images.php" method="POST">
                            <input type="hidden" name="recipe_name" value="<?= htmlspecialchars($recipe['name']) ?>">
                            <button type="submit" name="add_to_favorites" class="btn btn-warning btn-sm">Add to Favorites</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Favorite Recipes -->
        <?php if (!empty($favorites)): ?>
            <div class="container mt-5">
                <h2 class="text-center my-4">Your Favorite Recipes</h2>
                <ul class="list-group">
                    <?php foreach ($favorites as $favorite): ?>
                        <li class="list-group-item"><?= htmlspecialchars($favorite) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Recommended Recipes -->
        <div class="container mt-5">
            <h2 class="text-center my-4">Recommended Recipes</h2>
            <iframe src="recommended_recipes.php"></iframe>
        </div>

        <!-- Add a Recipe -->
        <div class="iframe-container">
            <h2 class="text-center my-4">Add a Recipe</h2>
            <iframe src="add_recipe.php"></iframe>
        </div>
    </div>
</body>
</html>