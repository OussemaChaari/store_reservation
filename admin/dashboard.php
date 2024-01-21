<?php
include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <section class="section">
        <header class="header">
            <div class="container">
                <a href="dashboard.php">Welcome <div class="fas fa-user"></div> <?php echo $_SESSION['admin_name']; ?></a>
                <nav class="navbar">
                    <ul class="navbar-list">
                        <li class="navbar-item">
                            <a href="dashboard.php" class="navbar-link">home</a>
                        </li>
                        <li class="navbar-item">
                            <a href="add_books.php" class="navbar-link">Add Book</a>
                        </li>
                        <li class="navbar-item">
                            <a href="books.php" class="navbar-link">All Books</a>
                        </li>
                        <li class="navbar-item">
                            <a href="reservations.php" class="navbar-link">Reservations</a>
                        </li>
                        <li class="navbar-item">
                            <a href="users.php" class="navbar-link">Users</a>
                        </li>
                    </ul>
                </nav>
                <div class="account-box">
                    <a href="logout.php">logout</a>
                </div>
            </div>
        </header>
    </section>
   
</body>

</html>