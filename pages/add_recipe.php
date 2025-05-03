<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/config.php'; // Include database connection

// Handle recipe submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_recipe'])) {
    if (!isset($_SESSION['user'])) {
        echo '<div class="alert alert-danger text-center">You must be logged in to add a recipe.</div>';
    } else {
        $recipe_name = htmlspecialchars($_POST['recipe_name']);
        $country = htmlspecialchars($_POST['country']);
        $description = htmlspecialchars($_POST['description']);
        $tags = htmlspecialchars($_POST['tags']); // New field for tags
        $image_path = null;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Create the uploads directory if it doesn't exist
            }
            $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
            $image_path = $upload_dir . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                echo '<div class="alert alert-danger text-center">Failed to upload the image. Please try again.</div>';
                $image_path = null;
            }
        }

        // Insert the recipe into the recipe_images table
        $query_recipe = "INSERT INTO recipe_images (recipe_name, image_path, tags) VALUES (?, ?, ?)";
        $stmt_recipe = $mysqli->prepare($query_recipe);
        $stmt_recipe->bind_param('sss', $recipe_name, $image_path, $tags);

        if ($stmt_recipe->execute()) {
            echo '<div class="alert alert-success text-center">Recipe added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger text-center">Failed to add the recipe. Please try again.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Recipe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .add-recipe-form {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .add-recipe-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="add-recipe-form">
            <h2>Add a Recipe</h2>
            <form action="add_recipe.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="recipe_name">Recipe Name</label>
                    <input type="text" name="recipe_name" id="recipe_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="country">Country of Origin</label>
                    <input type="text" name="country" id="country" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="tags">Tags (comma-separated)</label>
                    <input type="text" name="tags" id="tags" class="form-control" placeholder="e.g., pasta, tomato, beef" required>
                </div>
                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <input type="file" name="image" id="image" class="form-control-file" accept="image/*" required>
                </div>
                <button type="submit" name="submit_recipe" class="btn btn-primary btn-block">Submit Recipe</button>
            </form>
        </div>
    </div>
</body>
</html>