

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
</head>
<body>
    <h1>Add Category</h1>
    <form action="save_category.php" method="post" enctype="multipart/form-data">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" id="category_name" required>
        <br><br>
        <label for="image">Category Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <br><br>
        <button type="submit">Add Category</button>
    </form>
</body>
</html>