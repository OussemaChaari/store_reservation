<?php
include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $getImagePathQuery = "SELECT image_path FROM books WHERE id = ?";
    $stmtImagePath = mysqli_prepare($conn, $getImagePathQuery);
    mysqli_stmt_bind_param($stmtImagePath, 'i', $delete_id);
    mysqli_stmt_execute($stmtImagePath);
    mysqli_stmt_bind_result($stmtImagePath, $imagePath);
    mysqli_stmt_fetch($stmtImagePath);
    mysqli_stmt_close($stmtImagePath);
    $deleteQuery = "DELETE FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);    
    if (mysqli_stmt_execute($stmt)) {
        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath); // Delete the image file
        }
        header('location:books.php');
        exit();
    } else {
        echo "Error deleting book: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>All books</title>
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <section class="section">
        <h2 class="h2 section-title has-underline">
            All Books
            <span class="span has-before"></span>
        </h2>
        <div class="books">
            <?php
            $selectQuery = "SELECT * FROM books";
            $result = mysqli_query($conn, $selectQuery);
            if ($result) {
                // Fetch data and display each book in a card
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="card">
                        <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['name']; ?>" class="card-img-top">
                        <div class="card-body">
                            <div class="card-details">
                                <h3 class="section-subtitle">
                                    <?php echo $row['name']; ?>
                                </h3>
                                <div class="info_book">
                                    <div>
                                        <p class="card-text">
                                            <strong>Author:</strong>
                                            <?php echo $row['author']; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="card-text">
                                            <strong>Category:</strong>
                                            <?php echo $row['category']; ?>
                                        </p>
                                        <p class="card-text">
                                            <strong>Rating:</strong>
                                            <?php echo $row['rating']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn_book"><i class="fa fa-edit"></i></a>
                                <a href="?delete_id=<?= $row['id']; ?>" class="btn_book" onclick="return confirm('Are you sure you want to delete this book?');"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "Error: " . $selectQuery . "<br>" . mysqli_error($conn);
            }
            ?>
        </div>
    </section>


</body>

</html>