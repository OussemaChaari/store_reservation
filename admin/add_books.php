<?php 
include '../config.php';

session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:index.php');
}
if(isset($_POST['add_book'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $rating = $_POST['rating'];
    $targetDirectory = "uploads/";
    $uniqueId = uniqid();
    $targetFile = $targetDirectory . $uniqueId . "_" . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    
    if(is_numeric($rating)) {
        if($check !== false) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
            $imagePath = $targetFile;

            $insertQuery = "INSERT INTO books (name, description, category, author, rating, image_path) 
                            VALUES ('$name', '$description', '$category', '$author', $rating, '$imagePath')";

            if(mysqli_query($conn, $insertQuery)) {
                echo "Product added successfully!";
            } else {
                echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "File is not an image.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add books</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
<form action="" method="post" enctype="multipart/form-data" class="contact-form">
    <h2 class="h2 section-title has-underline">
        Add Book
        <span class="span has-before"></span>
    </h2>
    <input type="text" name="name" placeholder="enter book name" required class="input-field">
    <textarea name="description" id="description" placeholder="description book" cols="30" rows="10" class="input-field"></textarea>
    <input type="text" name="category" placeholder="enter category name" class="input-field" />
    <input type="text" name="author" placeholder="enter author name" class="input-field" />
    <input type="text" name="rating" id="rating" placeholder="enter rating book" class="input-field" />
   
    <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" required class="input-field">
    <input type="submit" value="add Book" name="add_book" class="btn btn-primary">
</form>

</body>

</html>