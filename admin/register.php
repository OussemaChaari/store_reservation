<?php

include '../config.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

    $select_users = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$email' AND password = '$pass'") or die('query failed');

    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'user already exist!';
    } else {
        if ($pass != $cpass) {
            echo "confirm password not matched!";
        } else {
            mysqli_query($conn, "INSERT INTO `admin`(name, email, password) VALUES('$name', '$email', '$cpass')") or die('query failed');
            echo "registered successfully!";
            header('location:index.php');
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <section class="section">
        <div class="container">
            <div class="wrapper">
                <form action="" method="post" class="contact-form">
                    <h2 class="h2 section-title has-underline">
                        register now
                        <span class="span has-before"></span>
                    </h2>
                    <input type="text" name="name" placeholder="enter your name" required class="input-field">
                    <input type="email" name="email" placeholder="enter your email" required class="input-field">
                    <input type="password" name="password" placeholder="enter your password" required class="input-field">
                    <input type="password" name="cpassword" placeholder="confirm your password" required class="input-field">
                    <input type="submit" name="register" value="register now" class="btn btn-primary">
                    <p>
                        <span>already have an account? </span>
                        <a href="index.php">login now</a>
                    </p>
                </form>
            </div>
        </div>
    </section>
</body>

</html>