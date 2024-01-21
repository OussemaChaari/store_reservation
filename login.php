<?php

include 'config.php';
session_start();

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

    if (mysqli_num_rows($select_users) > 0) {

        $row = mysqli_fetch_assoc($select_users);
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['user_id'] = $row['id'];
        header('location:index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <section class="section">
        <div class="container">
            <div class="wrapper">
                <form action="" method="post" class="contact-form">
                    <h2 class="h2 section-title has-underline">
                        login Now
                        <span class="span has-before"></span>
                    </h2>
                    <input type="email" name="email" placeholder="enter your email" required class="input-field">
                    <input type="password" name="password" placeholder="enter your password" required
                        class="input-field">
                    <input type="submit" name="login" value="login now" class="btn btn-primary">
                    <p>
                        <span>don't have an account?</span>
                        <a href="register.php">register now</a>
                    </p>
                </form>
            </div>
        </div>
    </section>
</body>

</html>