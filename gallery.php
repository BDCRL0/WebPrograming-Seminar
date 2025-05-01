<!-- gallery.php -->

<div class="back" id="gallery">
    <div class="container">
        <h1>Recipe Gallery</h1>
        <div class="imgmenu">
            <img src="assets/images/2.png" alt="Logo">
        </div>

        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="assets/images/7.png" alt="Dish 1">
                <h3>Delicious Salad</h3>
            </div>
            <div class="gallery-item">
                <img src="assets/images/8.png" alt="Dish 2">
                <h3>Hearty Soup</h3>
            </div>
            <div class="gallery-item">
                <img src="assets/images/9.png" alt="Dish 3">
                <h3>Exotic Dessert</h3>
            </div>
            <!-- More dishes can be added dynamically later -->
        </div>

        <!-- Upload Form Placeholder (only visible if logged in) -->
        <div class="upload-form">
            <h2>Upload Your Recipe Image</h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <input type="file" name="recipeImage" accept="image/*" required>
                <input type="text" name="recipeName" placeholder="Recipe Name" required>
                <button type="submit">Upload</button>
            </form>
        </div>
    </div>
</div>

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 30px 0;
}
.gallery-item {
    text-align: center;
}
.gallery-item img {
    width: 100%;
    height: auto;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.upload-form {
    margin-top: 50px;
    padding: 20px;
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    border-radius: 15px;
    text-align: center;
}
.upload-form input, .upload-form button {
    margin: 10px;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 80%;
}
.upload-form button {
    background-color: #f0ad4e;
    color: white;
    border: none;
}
</style>
