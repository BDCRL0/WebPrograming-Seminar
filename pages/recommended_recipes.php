<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/config.php'; // Include database connection

// Example recommended recipes (replace this with database queries if needed)
$recommended_recipes = [
    ['name' => 'Ramen', 'country' => 'Japan', 'description' => 'A Japanese noodle soup with a rich broth.', 'image' => 'images/ramen.jpg'],
    ['name' => 'Biryani', 'country' => 'India', 'description' => 'A flavorful rice dish cooked with spices and meat.', 'image' => 'images/biryani.jpg'],
    ['name' => 'Poutine', 'country' => 'Canada', 'description' => 'French fries topped with cheese curds and gravy.', 'image' => 'images/poutine.jpg'],
    ['name' => 'Shawarma', 'country' => 'Middle East', 'description' => 'A wrap filled with spiced meat and vegetables.', 'image' => 'images/shawarma.jpg'],
    ['name' => 'Empanadas', 'country' => 'Argentina', 'description' => 'Pastries filled with meat, cheese, or vegetables.', 'image' => 'images/empanadas.jpg'],
];

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended Recipes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .recommended-recipes {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            padding: 20px;
        }
        .recipe-card {
            text-align: center;
            width: 300px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .recipe-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .recipe-card h5 {
            font-size: 1.2rem;
            margin: 10px 0 5px;
        }
        .recipe-card p {
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center my-4">Recommended Recipes</h2>
        <div class="recommended-recipes">
            <?php foreach ($recommended_recipes as $recipe): ?>
                <div class="recipe-card">
                    <img src="<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['name']) ?>">
                    <h5><?= htmlspecialchars($recipe['name']) ?></h5>
                    <p><strong>Country:</strong> <?= htmlspecialchars($recipe['country']) ?></p>
                    <p><?= htmlspecialchars($recipe['description']) ?></p>
                    <form action="recommended_recipes.php" method="POST">
                        <input type="hidden" name="recipe_name" value="<?= htmlspecialchars($recipe['name']) ?>">
                        <button type="submit" name="add_to_favorites" class="btn btn-warning btn-sm">Add to Favorites</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>