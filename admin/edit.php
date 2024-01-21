<?php
include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing book details
    $selectQuery = "SELECT * FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $selectQuery);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$book) {
        echo "Book not found.";
        exit();
    }
} else {
    echo "Book ID not provided.";
    exit();
}

if (isset($_POST['update_book'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $rating = $_POST['rating'];

    // Check if a new image is uploaded
    if ($_FILES['image']['size'] > 0) {
        // Delete the old image
        unlink($book['image_path']);

        // Upload the new image
        $targetDirectory = "uploads/";
        $uniqueId = uniqid();
        $targetFile = $targetDirectory . $uniqueId . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = $targetFile;

        // Update the book details with the new image path
        $updateQuery = "UPDATE books SET name = ?, description = ?, category = ?, author = ?, rating = ?, image_path = ? WHERE id = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, 'ssssddsi', $name, $description, $category, $author, $rating, $imagePath, $id);
    } else {
        // No new image, update without changing the image
        $updateQuery = "UPDATE books SET name = ?, description = ?, category = ?, author = ?, rating = ? WHERE id = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, 'ssssddi', $name, $description, $category, $author, $rating, $id);
    }

    if (mysqli_stmt_execute($stmtUpdate)) {
        echo "Book updated successfully!";
        header('location:books.php');
        exit();
    } else {
        echo "Error updating book: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmtUpdate);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Edit Book</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <section class="section">
        <h2 class="h2 section-title has-underline">
            Edit Book
            <span class="span has-before"></span>
        </h2>
        <form action="" method="post" enctype="multipart/form-data" class="contact-form">
            <input type="text" name="name" placeholder="Enter book name" required class="input-field"
                value="<?= $book['name']; ?>">
            <textarea name="description" id="description" placeholder="Description of the book" cols="30" rows="10"
                class="input-field"><?= $book['description']; ?></textarea>
            <input type="text" name="category" placeholder="Enter category name" class="input-field"
                value="<?= $book['category']; ?>" />
            <input type="text" name="author" placeholder="Enter author name" class="input-field"
                value="<?= $book['author']; ?>" />
            <input type="text" name="rating" placeholder="Enter rating (1.0 to 5.0)" class="input-field"
                value="<?= $book['rating']; ?>" />
            
            <label for="image">Upload Image:</label>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="input-field">
            <input type="submit" value="Update" name="update_book" class="btn btn-primary" />
        </form>
    </section>
</body>

</html>